variable "vpc_id" {}
variable "vpc_cidr_block" {}
variable "subnets" {}
variable "target_group_front_arn" {}
variable "target_group_back_arn" {}
variable "security_group_id" {}
variable "name_prefix" {
  type = string
}
# variable "cluster_name" {
#   type = string
#   default = ""
# }
# variable "front_service_name" {
#   type = string
#   default = ""
# }
# variable "back_service_name" {
#   type = string
#   default = ""
# }
# タスク定義用のロール
variable "my_ecs_role_arn" {
  type = string
  default = ""
}
variable "log_groups" {
  type = list
}