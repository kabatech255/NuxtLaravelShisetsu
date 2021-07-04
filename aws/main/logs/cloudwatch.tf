resource "aws_cloudwatch_log_group" "re_web_log" {
  name = "re_web_log"
  retention_in_days = 3
}
resource "aws_cloudwatch_log_group" "re_app_log" {
  name = "re_app_log"
  retention_in_days = 3
}
resource "aws_cloudwatch_log_group" "re_front_app_log" {
  name = "re_front_app_log"
  retention_in_days = 3
}

output "log_group_name_web" {
  value = aws_cloudwatch_log_group.re_web_log.name
}
output "log_group_name_app" {
  value = aws_cloudwatch_log_group.re_app_log.name
}
output "log_group_name_front_app" {
  value = aws_cloudwatch_log_group.re_front_app_log.name
}