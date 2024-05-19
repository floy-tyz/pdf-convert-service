REPOSITORY_NAME=floytyz

APP_IMAGE_NAME=pdf-convert-app
NGINX_IMAGE_NAME=pdf-convert-nginx

PHP_IMAGE=php:8.3-fpm-alpine

default: build

build: build_app build_nginx

build_app:
	# build new image
	cd ../ && docker build --build-arg PHP=$(PHP_IMAGE) -f .kube/app/Dockerfile -t $(REPOSITORY_NAME)/$(APP_IMAGE_NAME):latest .
	# push image to dockerhub
	docker push $(REPOSITORY_NAME)/$(APP_IMAGE_NAME):latest
	# rollout deployment to update images
	kubectl rollout restart deployment $(APP_IMAGE_NAME)

build_nginx:
	# build new image
	cd ../ && docker build -f .kube/nginx/Dockerfile -t $(REPOSITORY_NAME)/$(NGINX_IMAGE_NAME):latest .
	# push image to dockerhub
	docker push $(REPOSITORY_NAME)/$(NGINX_IMAGE_NAME):latest
	# rollout deployment to update images
	kubectl rollout restart deployment $(NGINX_IMAGE_NAME)
