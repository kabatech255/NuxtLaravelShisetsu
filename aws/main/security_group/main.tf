variable "name" {}
variable "vpc_id" {}
variable "from_port" {} 
variable "to_port" {} 
variable "cidr_blocks" {} 

resource "aws_security_group" "sg" {
  name = var.name
  vpc_id = var.vpc_id

  tags = {
    Name = var.name
  }
}

resource "aws_security_group_rule" "ingress" {
  type = "ingress"
  from_port = var.from_port
  to_port = var.to_port
  protocol ="tcp"
  cidr_blocks = var.cidr_blocks
  security_group_id = aws_security_group.sg.id
}

resource "aws_security_group_rule" "egress" {
  type = "egress"
  from_port = 0
  to_port = 0
  protocol ="-1"
  cidr_blocks = ["0.0.0.0/0"]
  security_group_id = aws_security_group.sg.id
}