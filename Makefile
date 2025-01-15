CONTAINERNAME = cogify-php-1
EXEC = docker exec -it $(CONTAINERNAME) sh

.PHONY: start build stop ssh test infection

start:
	cd docker && docker compose up -d

build:
	test -e docker/.env && echo "docker/.env exists" || cp docker/.env.dist docker/.env >&2
	test -e docker/docker-compose.override.yaml && echo "docker/docker-compose.override.yaml exists" || cp docker/docker-compose.override.yaml.dist docker/docker-compose.override.yaml >&2
	cd docker && docker compose up -d
	$(EXEC) -c "composer install"

stop:
	cd docker && docker compose down

ssh:
	$(EXEC)

test:
	$(EXEC) -c "composer test"

complete-check:
	$(EXEC) -c "composer test-check && composer code-check"
