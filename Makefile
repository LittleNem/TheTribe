install-run:
	cd api && make -f Makefile up
	cd api && make -f Makefile sf c="doctrine:schema:drop -f --no-interaction"
	cd api && make -f Makefile sf c="doctrine:schema:update -f --no-interaction"
	cd api && make -f Makefile sf c="doctrine:fixtures:load --no-interaction"
	cd api && make -f Makefile sf c="lexik:jwt:generate-keypair --overwrite --no-interaction"
	cd front && make -f Makefile app-run

stop:
	cd api && make -f Makefile down
	cd front && make -f Makefile stop

