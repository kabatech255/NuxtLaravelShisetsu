variable "vpc_id" {
  type = string
}
variable "subnets" {
  type = list(string)
}
variable "domain_name" {
  type = string
}