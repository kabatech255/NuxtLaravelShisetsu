# インターネットゲートウェイ
resource "aws_internet_gateway" "default" {
  vpc_id = aws_vpc.default.id
  tags = {
    Name = "${var.rtb_tag_name}_igw"
  }
}

# パブリック用ルートテーブル
resource "aws_route_table" "public" {
  vpc_id = aws_vpc.default.id
  tags = {
    Name = "${var.rtb_tag_name}_public_rtb"
  }
}

# パブリック用ルートテーブルにigwのルート定義
resource "aws_route" "public" {
  route_table_id = aws_route_table.public.id
  gateway_id = aws_internet_gateway.default.id
  destination_cidr_block = "0.0.0.0/0"
}

# パブリックサブネット(./subnet.tf)に、パブリックルートテーブルを関連付け
resource "aws_route_table_association" "public0" {
  route_table_id = aws_route_table.public.id
  subnet_id = aws_subnet.public.0.id
}
resource "aws_route_table_association" "public1" {
  route_table_id = aws_route_table.public.id
  subnet_id = aws_subnet.public.1.id
}

# プライベート用ルートテーブル
resource "aws_route_table" "private" {
  vpc_id = aws_vpc.default.id
  tags = {
    Name = "${var.rtb_tag_name}_private_rtb"
  }
}

# プライベートサブネット(./subnet.tf)に、パブリックルートテーブルを関連付け
resource "aws_route_table_association" "private0" {
  route_table_id = aws_route_table.private.id
  subnet_id = aws_subnet.private.0.id
}

resource "aws_route_table_association" "private1" {
  route_table_id = aws_route_table.private.id
  subnet_id = aws_subnet.private.1.id
}