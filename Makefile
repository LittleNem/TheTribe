build:
	cd api && make -f Makefile build
	cd api && make -f Makefile sf c="doctrine:schema:drop -f --no-interaction"
	cd api && make -f Makefile sf c="doctrine:schema:update -f --no-interaction"
	cd api && make -f Makefile sf c="doctrine:fixtures:load --no-interaction"
	cd api && make -f Makefile sf c="lexik:jwt:generate-keypair --overwrite --no-interaction"
	cd front && make -f Makefile build

run:
	cd api && make -f Makefile up
		cd front && make -f Makefile run

stop:
	cd api && make -f Makefile down
	cd front && make -f Makefile stop

