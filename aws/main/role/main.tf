variable "json_path_prefix" {
  type = string
  default = "."
}

variable "ecs_policy_arns" {
  type = list(string)
  default = [
    "arn:aws:iam::aws:policy/AmazonS3FullAccess",
    "arn:aws:iam::aws:policy/AmazonECS_FullAccess",
    "arn:aws:iam::aws:policy/service-role/AmazonEC2ContainerServiceforEC2Role"
  ]
}

variable "ec2_policy_arns" {
  type = list(string)
  default = [
    "arn:aws:iam::aws:policy/service-role/AmazonEC2ContainerServiceforEC2Role"
  ]
}

# ロールの信頼ポリシー
data "aws_iam_policy_document" "ecs_assume_role" {
  source_json = file("${var.json_path_prefix}/policies/ecs_policy_principal.json")
}

data "aws_iam_policy_document" "ec2_assume_role" {
  source_json = file("${var.json_path_prefix}/policies/ec2_policy_principal.json")
}


# ロールの作成
resource "aws_iam_role" "my_ecs_role" {
  name = "MyECSRole"
  assume_role_policy = data.aws_iam_policy_document.ecs_assume_role.json
}

resource "aws_iam_role" "ecs_instance_role" {
  name = "ecsInstanceRole"
  assume_role_policy = data.aws_iam_policy_document.ec2_assume_role.json
}

# ポリシーの作成
resource "aws_iam_policy" "ecs_env_connection" {
  name = "ECS_ENV_CONNECTION"
  # SSMのパラメータストアにアクセス、KMSによる復号等の権限
  policy = file("${var.json_path_prefix}/policies/ecs_env_connection.json")
}

# 各ロールにポリシーをアタッチ
resource "aws_iam_role_policy_attachment" "my_ecs_env_connect_attachment" {
  role = aws_iam_role.my_ecs_role.name
  policy_arn = aws_iam_policy.ecs_env_connection.arn
}

resource "aws_iam_role_policy_attachment" "my_ecs_other_attachment" {
  for_each = {
    for arn in var.ecs_policy_arns : arn => arn
  }
  role = aws_iam_role.my_ecs_role.name
  policy_arn = each.value
}

resource "aws_iam_role_policy_attachment" "ecs_instance_env_connect_attachment" {
  role = aws_iam_role.ecs_instance_role.name
  policy_arn = aws_iam_policy.ecs_env_connection.arn
}

resource "aws_iam_role_policy_attachment" "ecs_instance_other_attachment" {
  for_each = {
    for arn in var.ec2_policy_arns : arn => arn
  }
  role = aws_iam_role.ecs_instance_role.name
  policy_arn = each.value
}