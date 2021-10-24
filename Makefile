init:
	docker-compose build --force-rm --no-cache
	make up

up:
	docker-compose up -d
	echo "App is running"
