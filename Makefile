up:
	cd docker && docker compose up -d
down:
	cd docker && docker compose down
exec:
	docker exec -it docker-kata-1 /bin/sh
test:
	docker exec docker-kata-1 /