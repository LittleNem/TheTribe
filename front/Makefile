app-run:
	docker build -t ps-container:dev .
	docker run -it --name reactribe --rm \
	-v ${PWD}:/app \
	-v /app/node_modules \
	-p 3001:3000 \
	-e CHOKIDAR_USEPOLLING=true \
	ps-container:dev

stop:
	docker kill reactribe
