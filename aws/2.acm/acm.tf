data "aws_route53_zone" "default" {
  name = var.domain_name
}

provider "aws" {
  alias  = "tokyo"
  region = "ap-northeast-1"
}

provider "aws" {
  alias  = "virginia"
  region = "us-east-1"
}

# ALBと紐付ける
resource "aws_acm_certificate" "default" {
  domain_name = "exam.${data.aws_route53_zone.default.name}"
  subject_alternative_names = []
  validation_method = "DNS"
  # リージョン：東京
  provider = aws.tokyo

  lifecycle {
    create_before_destroy = true
  }

  tags = {
    Name = var.pj_prefix
  }
}

# cloudfrontと紐付ける
resource "aws_acm_certificate" "cdn" {
  domain_name = "asset.${data.aws_route53_zone.default.name}"
  subject_alternative_names = []
  validation_method = "DNS"
  # リージョン：バージニア北部
  provider = aws.virginia

  lifecycle {
    create_before_destroy = true
  }

  tags = {
    Name = "${var.pj_prefix}_cdn"
  }
}

# CNAMEレコード追加
resource "aws_route53_record" "default_certificate" {
  for_each = {
    for dvo in aws_acm_certificate.default.domain_validation_options : dvo.domain_name => {
      name   = dvo.resource_record_name
      record = dvo.resource_record_value
      type   = dvo.resource_record_type
    }
  }

  zone_id = data.aws_route53_zone.default.zone_id
  name = each.value.name
  type = each.value.type
  records = [each.value.record]
  ttl = 60
}

# 検証の待機
resource "aws_acm_certificate_validation" "default" {
  certificate_arn = aws_acm_certificate.default.arn
  validation_record_fqdns = [for record in aws_route53_record.default_certificate : record.fqdn]
}

# CNAMEレコード追加(バージニア)
resource "aws_route53_record" "cdn_certificate" {
  provider = aws.virginia
  for_each = {
    for dvo in aws_acm_certificate.cdn.domain_validation_options : dvo.domain_name => {
      name   = dvo.resource_record_name
      record = dvo.resource_record_value
      type   = dvo.resource_record_type
    }
  }

  zone_id = data.aws_route53_zone.default.zone_id
  name = each.value.name
  type = each.value.type
  records = [each.value.record]
  ttl = 60
}

# 検証の待機
resource "aws_acm_certificate_validation" "cdn" {
  provider = aws.virginia
  certificate_arn = aws_acm_certificate.cdn.arn
  validation_record_fqdns = [for record in aws_route53_record.cdn_certificate : record.fqdn]
}
