data "aws_acm_certificate" "default" {
  domain = "exam.${data.aws_route53_zone.default.name}"
  statuses = ["ISSUED"]
}

resource "aws_lb_listener" "https" {
  load_balancer_arn = aws_lb.default.arn
  port = "443"
  protocol = "HTTPS"
  certificate_arn = data.aws_acm_certificate.default.arn
  ssl_policy = "ELBSecurityPolicy-2016-08"
  
  default_action {
    type = "fixed-response"

    fixed_response {
      content_type = "text/plain"
      message_body = "これはHTTPSです"
      status_code = "200"
    }
  }
}
