ROOT=$(shell pwd)
BUILD_NUMBER=$(shell git rev-list --count HEAD)
BUILD=$(shell git log --pretty=format:'%h' -n 1)
service_name='api-vk-mezzio'

build:
	VERSION="1.0.$(BUILD_NUMBER) Rev $(BUILD)" docker build  --build-arg ssh_prv_key="$$(cat ~/.ssh/id_rsa)" --build-arg ssh_pub_key="$$(cat ~/.ssh/id_rsa.pub)" -t ${service_name}:$(BUILD) -f docker/php/Dockerfile .
	docker tag ${service_name}:${BUILD}
