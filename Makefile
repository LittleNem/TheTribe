build:
	cd api && make -f Makefile build
	cd front && make -f Makefile build

run:
	cd api && make -f Makefile up
	cd front && make -f Makefile run

data:
	cd api && make -f Makefile build-data

stop:
	cd api && make -f Makefile down
	cd front && make -f Makefile stop

