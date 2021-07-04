provider "aws" {
  alias  = "virginia"
  region = "us-east-1"
}

data "aws_acm_certificate" "cdn" {
  domain = "asset.${data.aws_route53_zone.default.name}"
  statuses = ["ISSUED"]
  provider = aws.virginia
}

resource "aws_cloudfront_distribution" "default" {

  origin {
    domain_name = aws_s3_bucket.public.bucket_regional_domain_name
    origin_id = "S3-${aws_s3_bucket.public.bucket_regional_domain_name}"

    # s3_origin_config {
    #   origin_access_identity = 
    # }
  }

  default_cache_behavior {
    allowed_methods = ["GET", "HEAD"]
    cached_methods   = ["GET", "HEAD"]
    viewer_protocol_policy = "allow-all"
    target_origin_id = "S3-${aws_s3_bucket.public.bucket_regional_domain_name}"
    forwarded_values {
      query_string = false
      cookies {
        forward = "none"
      }
    }
  }

  restrictions {
    geo_restriction {
      restriction_type = "none"
    }
  }

  enabled = true
  is_ipv6_enabled = true
  default_root_object = "index.html"
  aliases = ["asset.${data.aws_route53_zone.default.name}"]

  viewer_certificate {
    cloudfront_default_certificate = false
    acm_certificate_arn = data.aws_acm_certificate.cdn.arn
    ssl_support_method = "sni-only"
  }

}

# Aレコードのエイリアス追加
resource "aws_route53_record" "cdn" {
  zone_id = data.aws_route53_zone.default.zone_id
  name = format("asset.%s", data.aws_route53_zone.default.name)
  type = "A"

  alias {
    name = aws_cloudfront_distribution.default.domain_name
    zone_id = aws_cloudfront_distribution.default.hosted_zone_id
    evaluate_target_health = true
  }
}