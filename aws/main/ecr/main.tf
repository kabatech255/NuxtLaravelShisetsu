variable "repos" {
  type = map(map(string))
  default = {
    1 = {
      name = "web"
    },
    2 = {
      name = "app"
    },
    3 = {
      name = "front_app"
    }
  }
}

resource "aws_ecr_repository" "web" {
  for_each = var.repos
  name = "${ var.repo_name_prefix }/${ lookup(each.value, "name") }"
}

resource "aws_ecr_lifecycle_policy" "web" {
  for_each = var.repos
  repository = "${ var.repo_name_prefix }/${ lookup(each.value, "name") }"
  policy = <<EOF
  {
    "rules": [
      {
        "rulePriority": 1,
        "description": "keep last 15 release tagged images",
        "selection": {
          "tagStatus": "tagged",
          "tagPrefixList": ["release"],
          "countType": "imageCountMoreThan",
          "countNumber": 15
        },
        "action": {
          "type": "expire"
        }
      }
    ]
  }
  EOF
}