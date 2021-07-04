resource "aws_subnet" "public" {
  count = length(var.public_subnets)
  vpc_id = aws_vpc.default.id
  cidr_block = values(var.public_subnets)[count.index].cidr_block
  map_public_ip_on_launch = true
  availability_zone = values(var.public_subnets)[count.index].availability_zone
  tags = {
    Name = values(var.public_subnets)[count.index].name
  }
}

resource "aws_subnet" "private" {
  count = "${ length(var.private_subnets) }"
  vpc_id = aws_vpc.default.id
  cidr_block = values(var.private_subnets)[count.index].cidr_block
  map_public_ip_on_launch = false
  availability_zone = values(var.private_subnets)[count.index].availability_zone
  tags = {
    Name = values(var.private_subnets)[count.index].name
  }
}


