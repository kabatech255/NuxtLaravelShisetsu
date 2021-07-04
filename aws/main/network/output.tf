output "vpc_id" {
  value = aws_vpc.default.id
}

output "vpc_cidr_block" {
  value = aws_vpc.default.cidr_block
}

output "subnet_public0_id" {
  value = aws_subnet.public.0.id
}

output "subnet_public1_id" {
  value = aws_subnet.public.1.id
}

output "subnet_private0_id" {
  value = aws_subnet.private.0.id
}

output "subnet_private1_id" {
  value = aws_subnet.private.1.id
}