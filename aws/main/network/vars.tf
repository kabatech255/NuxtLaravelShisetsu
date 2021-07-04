variable "vpc_tag_name" {
  type = string
  default = "default"
}

variable "rtb_tag_name" {
  type = string
  default = "default"
}

variable "vpc_cidr_block" {
  type = string
  default = "10.0.0.0/16"
}

variable "public_subnets" {
  default = {
    0 = {
      cidr_block = "10.0.1.0/24"
      availability_zone = "ap-northeast-1a"
      # TODO: "risk-exam"のところを変数にすると[Variables may not be used here.]と怒られる
      name = "risk-exam-public0"
    }
    1 = {
      cidr_block = "10.0.2.0/24"
      availability_zone = "ap-northeast-1c"
      # TODO: "risk-exam"のところを変数にすると[Variables may not be used here.]と怒られる
      name = "risk-exam-public1"
    }
  }
}

variable "private_subnets" {
  default = {
    0 = {
      cidr_block = "10.0.61.0/24"
      availability_zone = "ap-northeast-1a"
      # TODO: "risk-exam"のところを変数にすると[Variables may not be used here.]と怒られる
      name = "risk-exam-private0"
    }
    1 = {
      cidr_block = "10.0.62.0/24"
      availability_zone = "ap-northeast-1c"
      # TODO: "risk-exam"のところを変数にすると[Variables may not be used here.]と怒られる
      name = "risk-exam-private1"
    }
  }
}