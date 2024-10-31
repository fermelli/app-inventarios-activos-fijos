NAME=app-inventarios-activos-fijos
REPO=

ifeq ($(REPO),)
URI=$(NAME)
else
URI=$(REPO)/$(NAME)
endif

VERSION=1.0.0
TAG=$(URI):$(VERSION)

build:
	rm -rf public/build && ENV_FILE=.env.docker yarn build --mode docker
	docker build -t $(TAG) . --no-cache
	docker system prune -f
save:
	docker save -o ./$(NAME)_$(VERSION).tar $(TAG)
load:
	docker load -i ./$(NAME)_$(VERSION).tar