resource "aws_lb_listener" "http_redirect" {
  load_balancer_arn = aws_lb.default.arn
  port = "8080"
  protocol = "HTTP"

  default_action {
    type = "redirect"

    redirect {
      port = "443"
      protocol = "HTTPS"
      status_code = "HTTP_301"
    }
  }
}
