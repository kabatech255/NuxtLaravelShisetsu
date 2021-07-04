resource "aws_db_parameter_group" "default" {
  name = "risk-exam"
  family = "mysql5.7"


  parameter {
    name = "character_set_client"
    value = "utf8mb4"
  }

  parameter {
    name = "character_set_connection"
    value = "utf8mb4"
  }

  parameter {
    name = "character_set_database"
    value = "utf8mb4"
  }

  parameter {
    name = "character_set_results"
    value = "utf8mb4"  
  }

  parameter {
    name = "character_set_server"
    value = "utf8mb4"
  }

  parameter {
    name = "collation_connection"
    value = "utf8mb4_unicode_ci"
  }

  parameter {
    name = "collation_server"
    value = "utf8mb4_unicode_ci"
  }
}

resource "aws_db_option_group" "default" {
  name = "risk-exam-option"
  engine_name = "mysql"
  major_engine_version = "5.7"

  option {
    option_name = "MARIADB_AUDIT_PLUGIN"
  }
}

module "mysql_sg" {
  source = "../security_group"
  name = "risk_exam_mysql_sg"
  vpc_id = var.vpc_id
  from_port = 3306
  to_port = 3306
  cidr_blocks = [var.vpc_cidr_block]
}

resource "aws_db_subnet_group" "default" {
  name = "risk-exam"
  subnet_ids = var.subnet_ids
}

resource "aws_db_instance" "default" {
  identifier = "risk-exam"
  engine = "mysql"
  engine_version = "5.7.25"
  instance_class = "db.t2.micro"
  allocated_storage = 20
  max_allocated_storage = 50
  storage_type = "gp2"
  name = "risk_exam"
  username = var.db_user
  password = var.db_pass
  # multi_az = true
  publicly_accessible = false
  backup_window = "03:10-03:40"
  backup_retention_period = 7
  maintenance_window = "mon:10:10-mon:10:40"
  auto_minor_version_upgrade = false
  # 本番では削除保護をtrueにする↓
  deletion_protection = false
  # destroy時は一旦trueにしてapply
  skip_final_snapshot = true
  port = 3306
  apply_immediately = false
  vpc_security_group_ids = [module.mysql_sg.security_group_id]
  parameter_group_name = aws_db_parameter_group.default.name
  option_group_name = aws_db_option_group.default.name
  db_subnet_group_name = aws_db_subnet_group.default.name

  lifecycle {
    ignore_changes = [password]
  }

}