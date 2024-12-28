SHELL=/bin/bash
DOCKER_COMPOSE_TEST=docker/test.yml
APP_NAME="challenge-2024-12-27"
IMAGE_NAME_BETA="challenge-2024-12-27-beta"
IMAGE_NAME_PROD="challenge-2024-12-27-prod"
TEST_CONTAINER_NAME="php-test"
AWS_ID=368539636127
AWS_REGION=us-east-2
AWS_REGISTRY=$(AWS_ID).dkr.ecr.$(AWS_REGION).amazonaws.com
AWS_PROFILE=insignia-ecr
COMMAND=$(MAKECMDGOALS)
WORDS  = $(words $(COMMAND))
ARGS = $(wordlist 2,$(WORDS),$(COMMAND))


ecr:
	@aws ecr get-login-password --profile $(ARGS) --region $(AWS_REGION) | docker login --username AWS --password-stdin $(AWS_REGISTRY)

build-base-php8:
	@docker build  --platform linux/amd64 -t $(AWS_REGISTRY)/roquendo/php8:latest -f images/Dockerfile.php8 . 

build-base-php8-nginx:
	@docker build  --platform linux/amd64 -t $(AWS_REGISTRY)/roquendo/php8-nginx:latest -f images/Dockerfile.php8-nginx . 

build-base-php8-nginx-cron:
	@docker build  --platform linux/amd64 -t $(AWS_REGISTRY)/roquendo/php8-nginx-cron:latest -f images/Dockerfile.php8-nginx-cron . 

build-base-php8-test:
	@docker build  --platform linux/amd64 -t $(AWS_REGISTRY)/roquendo/php8-test:latest -f images/Dockerfile.php8-test . 

build-base:
	@make build-base-php8
	@make build-base-php8-test
	@make build-base-php8-nginx
	@make build-base-php8-nginx-cron

push-base-php8:
	@docker push $(AWS_REGISTRY)/roquendo/php8:latest 

push-base-php8-nginx:
	@docker push $(AWS_REGISTRY)/roquendo/php8-nginx:latest 

push-base-php8-nginx-cron:
	@docker push $(AWS_REGISTRY)/roquendo/php8-nginx-cron:latest 

push-base-php8-test:
	@docker push $(AWS_REGISTRY)/roquendo/php8-test:latest 
	
push-base:
	@make push-base-php8
	@make push-base-php8-test
	@make push-base-php8-nginx
	@make push-base-php8-nginx-cron

build-beta:
	@docker build  --platform linux/amd64 -t $(AWS_REGISTRY)/$(IMAGE_NAME_BETA):latest -f Dockerfile . 

push-beta:
	@docker push $(AWS_REGISTRY)/$(IMAGE_NAME_BETA):latest 

build-prod:
	@docker build  --platform linux/amd64 -t $(AWS_REGISTRY)/$(IMAGE_NAME_PROD):latest -f Dockerfile . 

push-prod:
	@docker push $(AWS_REGISTRY)/$(IMAGE_NAME_PROD):latest 

test:	
	# @make build-base
	@docker rm -f -v db-test php-test
	@rm -rf src/bootstrap/cache/* src/storage/framework/cache/*
	@docker-compose -p $(APP_NAME) -f $(DOCKER_COMPOSE_TEST) up -d --force-recreate --remove-orphans 
	@docker exec -t $(TEST_CONTAINER_NAME) composer install
	@docker exec -t $(TEST_CONTAINER_NAME) php artisan migrate --seed
	@docker exec -t $(TEST_CONTAINER_NAME) bash -c "./vendor/bin/phpunit --testdox --do-not-cache-result --configuration phpunit.xml --coverage-html '/platform/public/__TEST__'" 
	@open src/public/__TEST__/index.html

install:
	@echo Installing git hooks...
	@cp .gitlab/hooks/pre-commit .git/hooks
	@cp .gitlab/hooks/prepare-commit-msg .git/hooks
	@chmod u+x .git/hooks/pre-commit .git/hooks/prepare-commit-msg
	@echo Done!

