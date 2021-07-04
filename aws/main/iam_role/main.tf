variable "name" {}
variable "policy" {}
variable "identifier" {}


data "aws_iam_policy_document" "risk_exam_admin" {
  statement {
    effect = "Allow"

    actions = ["*"]
    resources = ["*"]

    principals {
      type        = "Service"
      identifiers = ["ec2.amazonaws.com"]
    }
  }
}

resource "aws_iam_policy" "risk_exam_admin" {
  name = "risk_exam_admin"
  policy = file("./iam_role/administrator.json")
}

resource "aws_iam_role" "risk_exam_admin" {
  name = "risk_exam_admin"
  assume_role_policy = file("./iam_role/administrator.json")
}

resource "aws_iam_role_policy_attachment" "risk_exam_admin" {
  role = aws_iam_role.risk_exam_admin.name
  policy_arn = aws_iam_policy.risk_exam_admin.arn
}

output "iam_role_arn" {
  value = aws_iam_role.risk_exam_admin.arn
}

output "iam_role_name" {
  value = aws_iam_role.risk_exam_admin.name
}
