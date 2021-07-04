provider "aws" {
  region = "ap-northeast-1"
}

# ECSでSSMのパラメータストアのパラメータにアクセスする等のロール
module "role" {
  source = "./role"
  json_path_prefix = "./role"
}

# cdn
module "cdn" {
  source = "./cdn"
  domain_name = var.domain_name
  json_path_prefix = "./cdn"
}

# network
module "network" {
  source = "./network"
  vpc_tag_name = var.pj_name_kebab
  rtb_tag_name = var.pj_name_snake
  vpc_cidr_block = "10.0.0.0/16"
}

# ALB
module "alb" {
  source = "./load_balancer"
  vpc_id = module.network.vpc_id
  # networkディレクトリのoutput.tfで出力したやつを使う
  subnets = [
    module.network.subnet_public0_id,
    module.network.subnet_public1_id,
  ]
  domain_name = var.domain_name
}

# CloudWatch
module "cloudwatch" {
  source = "./logs"
}

# ECR
module "ecr" {
  source = "./ecr"
  repo_name_prefix = var.pj_name_kebab
}

# ECS
module "ecs" {
  source = "./orchestration"
  vpc_id = module.network.vpc_id
  vpc_cidr_block = module.network.vpc_cidr_block
  subnets = [
    module.network.subnet_public0_id,
    module.network.subnet_public1_id,
  ]
  target_group_front_arn = module.alb.target_group_front_arn
  target_group_back_arn = module.alb.target_group_back_arn
  security_group_id = module.alb.security_group_http_id
  name_prefix = var.pj_name_kebab
  # cluster_name = var.cluster_name
  # front_service_name = var.front_service_name
  # back_service_name = var.back_service_name
  my_ecs_role_arn = module.role.my_ecs_role_arn
  log_groups = [
    module.cloudwatch.log_group_name_web,
    module.cloudwatch.log_group_name_app,
    module.cloudwatch.log_group_name_front_app
  ]
}

# RDS
module "mysql_rds" {
  source = "./db"
  subnet_ids = [
    module.network.subnet_public0_id,
    module.network.subnet_public1_id,
  ]
  vpc_id = module.network.vpc_id
  vpc_cidr_block = module.network.vpc_cidr_block
  db_user = var.db_user
  db_pass = var.db_pass
}