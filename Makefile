SHELL=/bin/bash
DOCKER_COMPOSE_TEST=docker/test.yml
APP_NAME="challenge-2024-12-27"
IMAGE_NAME_BETA="challenge-2024-12-27-beta"
IMAGE_NAME_PROD="challenge-2024-12-27-prod"
TEST_CONTAINER_NAME="php-test"
AWS_ID=368539636127
AWS_REGION=us-east-1
AWS_REGISTRY=$(AWS_ID).dkr.ecr.$(AWS_REGION).amazonaws.com
AWS_PROFILE=insignia-rodolfoquendo
COMMAND=$(MAKECMDGOALS)
WORDS  = $(words $(COMMAND))
ARGS = $(wordlist 2,$(WORDS),$(COMMAND))


ecr:
	@aws ecr get-login-password --profile $(ARGS) --region $(AWS_REGION) | docker login --username AWS --password-stdin $(AWS_REGISTRY)

build-beta:
	@docker build  --platform linux/amd64 -t $(AWS_REGISTRY)/$(IMAGE_NAME_BETA):latest -f Dockerfile . 

push-beta:
	@docker push $(AWS_REGISTRY)/$(IMAGE_NAME_BETA):latest 

build-prod:
	@docker build  --platform linux/amd64 -t $(AWS_REGISTRY)/$(IMAGE_NAME_PROD):latest -f Dockerfile . 

push-prod:
	@docker push $(AWS_REGISTRY)/$(IMAGE_NAME_PROD):latest 

test:	
	@if [ $(AWS_PROFILE) != "" ] ; then aws ecr get-login-password --profile $(AWS_PROFILE) --region $(AWS_REGION) | docker login --username AWS --password-stdin $(AWS_REGISTRY); fi
	@docker rm -f -v afl-db-test afl-php-test
	@rm -rf src/bootstrap/cache/*
	@docker-compose -p $(APP_NAME) -f $(DOCKER_COMPOSE_TEST) pull 
	@docker-compose -p $(APP_NAME) -f $(DOCKER_COMPOSE_TEST) up -d --force-recreate --remove-orphans 
	@docker exec -t $(TEST_CONTAINER_NAME) composer install
	@docker exec -t $(TEST_CONTAINER_NAME) bash -c "./vendor/bin/phpunit --testdox --do-not-cache-result --configuration phpunit.xml --coverage-html '/afluenta-platform/public/__TEST__'" 
	@open src/public/__TEST__/index.html

install:
	@echo Installing git hooks...
	@cp .gitlab/hooks/pre-commit .git/hooks
	@cp .gitlab/hooks/prepare-commit-msg .git/hooks
	@chmod u+x .git/hooks/pre-commit .git/hooks/prepare-commit-msg
	@echo Done!

