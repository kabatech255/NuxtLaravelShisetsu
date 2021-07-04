data "aws_route53_zone" "default" {
  name = var.domain_name
}

module "sg_https" {
  source = "../security_group"
  name = "risk-exam-https-sg"
  vpc_id = var.vpc_id
  from_port = "443"
  to_port = "443"
  cidr_blocks = ["0.0.0.0/0"]
}

module "sg_http" {
  source = "../security_group"
  name = "risk-exam-http-sg"
  vpc_id = var.vpc_id
  from_port = "0"
  to_port = "65535"
  cidr_blocks = ["10.0.0.0/16"]
}

module "sg_http_redirect" {
  source = "../security_group"
  name = "risk-exam-http-redirect-sg"
  vpc_id = var.vpc_id
  from_port = "8080"
  to_port = "8080"
  cidr_blocks = ["0.0.0.0/0"]
}

resource "aws_lb" "default" {
  name = "risk-exam-alb"
  load_balancer_type = "application"
  internal = false
  idle_timeout = 60
  # destroy時は一旦falseにしてapply
  enable_deletion_protection = false

  # public0とpublic1
  subnets = var.subnets

  security_groups = [
    module.sg_https.security_group_id,
    module.sg_http.security_group_id,
    module.sg_http_redirect.security_group_id,
  ]
}

# フロント用ターゲットグループ
resource "aws_lb_target_group" "front" {
  name = "risk-exam-tg-front"
  target_type = "instance"
  vpc_id = var.vpc_id
  port = 3000
  protocol = "HTTP"
  deregistration_delay = 300

  health_check {
    path = "/"
    healthy_threshold = 2
    unhealthy_threshold = 5
    timeout = 20
    interval = 100
    matcher = 200
    port = "traffic-port"
    protocol = "HTTP"
  }

  depends_on = [aws_lb.default]
}

# APIサーバ用ターゲットグループ
resource "aws_lb_target_group" "back" {
  name = "risk-exam-tg-back"
  target_type = "instance"
  vpc_id = var.vpc_id
  port = 80
  protocol = "HTTP"
  deregistration_delay = 300

  health_check {
    path = "/api"
    healthy_threshold = 2
    unhealthy_threshold = 5
    timeout = 10
    interval = 100
    matcher = 200
    port = "traffic-port"
    protocol = "HTTP"
  }

  depends_on = [aws_lb.default]
}

# フロント用リスナールール
resource "aws_lb_listener_rule" "front" {
  listener_arn = aws_lb_listener.https.arn
  priority = 100

  action {
    type = "forward"
    target_group_arn = aws_lb_target_group.front.arn
  }

  condition {
    path_pattern {
      values = ["/*"]
    }
  }
}

# APIサーバ用リスナールール
resource "aws_lb_listener_rule" "back" {
  listener_arn = aws_lb_listener.https.arn
  priority = 99

  action {
    type = "forward"
    target_group_arn = aws_lb_target_group.back.arn
  }

  condition {
    path_pattern {
      values = ["/api/*"]
    }
  }
}

# Aレコードの作成
resource "aws_route53_record" "default" {
  zone_id = data.aws_route53_zone.default.zone_id
  name = "exam.${data.aws_route53_zone.default.name}"
  type = "A"

  alias {
    name = aws_lb.default.dns_name
    zone_id = aws_lb.default.zone_id
    evaluate_target_health = true
  }
}

