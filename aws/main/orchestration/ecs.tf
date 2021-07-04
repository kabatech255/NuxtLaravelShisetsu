# フロント用タスク定義
resource "aws_ecs_task_definition" "front" {
  family = "${var.name_prefix}-task-front"
  network_mode = "bridge"
  execution_role_arn = var.my_ecs_role_arn
  requires_compatibilities = []
  # compatibilities = ["EC2"]
  container_definitions = templatefile("./orchestration/container_definition_front.json", {
    log_groups = var.log_groups
  })
}

# APIサーバ用タスク定義
resource "aws_ecs_task_definition" "back" {
  family = "${var.name_prefix}-task-back"
  network_mode = "bridge"
  execution_role_arn = var.my_ecs_role_arn
  requires_compatibilities = []
  # compatibilities = ["EC2"]
  container_definitions = templatefile("./orchestration/container_definition_back.json", {
    log_groups = var.log_groups
  })
}

# クラスター
# data "aws_ssm_parameter" "amzn2_for_ecs_ami" {
#   name = "/aws/service/ecs/optimized-ami/amazon-linux-2/recommended/image_id"
# }

# resource "aws_launch_template" "default" {
#   name          = "default"
#   image_id      = data.aws_ssm_parameter.amzn2_for_ecs_ami.value
#   instance_type = "t2.small"
#   ebs_optimized = true
#   user_data = base64encode(templatefile("./orchestration/userdata.sh", {
#     cluster_name = var.cluster_name
#   }))

#   block_device_mappings {
#     device_name = "/dev/sda1"
#     ebs {
#       volume_size = 30
#       volume_type = "gp2"
#     }
#   }
# }

# resource "aws_autoscaling_group" "default" {
#   name = "default"

#   launch_template {
#     id = aws_launch_template.default.id
#     version = "$Latest"
#   }

#   protect_from_scale_in = true
#   max_size = 1
#   min_size = 1
#   availability_zones = ["ap-northeast-1a"]
# }

# resource "aws_ecs_capacity_provider" "default" {
#   name = "ec2"

#   auto_scaling_group_provider {
#     auto_scaling_group_arn = aws_autoscaling_group.default.arn
#     managed_termination_protection = "ENABLED"

#     managed_scaling {
#       maximum_scaling_step_size = 1
#       minimum_scaling_step_size = 1
#       status = "ENABLED"
#       target_capacity = 100
#     }
#   }
# }

# resource "aws_ecs_cluster" "default" {
#   name = var.cluster_name
#   capacity_providers = [aws_ecs_capacity_provider.default.name]

#   default_capacity_provider_strategy {
#     capacity_provider = aws_ecs_capacity_provider.default.name
#     weight = 1
#     base = 0
#   }
# }

# ECSサービス
# resource "aws_ecs_service" "back_service" {
#   name = var.back_service_name
#   cluster = aws_ecs_cluster.default.arn
#   task_definition = aws_ecs_task_definition.back.arn
#   desired_count = 1
#   health_check_grace_period_seconds = 30
#   launch_type = "EC2"
#   iam_role = "arn:aws:iam::015211996394:role/aws-service-role/ecs.amazonaws.com/AWSServiceRoleForECS"

#   # network_configuration {
#   #   security_groups = [var.security_group_id]
#   #   subnets = var.subnets
#   # }

#   load_balancer {
#     target_group_arn = var.target_group_back_arn
#     container_name = "web"
#     container_port = 80
#   }

#   lifecycle {
#     ignore_changes = [task_definition]
#   }
# }

