NAME=app-inventarios-activos-fijos
VERSION=1.0.0

build:
	npm run build -- --mode docker
	docker build -t $(NAME):$(VERSION) .
	docker system prune -f