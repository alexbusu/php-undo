PHP_VERSIONS = 7.2 7.3 7.4 8.0 8.1 8.2 8.3 8.4
TEST_TARGETS = $(addprefix test-,$(PHP_VERSIONS))

.PHONY: help test $(TEST_TARGETS)

help:
	@echo "Usage: make <target>"
	@echo ""
	@echo "Targets:"
	@echo "  test             Run tests on all supported PHP versions"
	@echo "  test-<version>   Run tests on a specific PHP version (e.g., make test-8.1)"
	@echo ""
	@echo "Supported versions: $(PHP_VERSIONS)"

test: $(TEST_TARGETS)

$(TEST_TARGETS): test-%:
	@echo ">>> Running tests on PHP $*"
	docker run --rm -v $(CURDIR):/app -w /app mileschou/phalcon:$*-cli \
		sh -c "echo 'deb http://archive.debian.org/debian buster main' > /etc/apt/sources.list && \
		echo 'deb http://archive.debian.org/debian-security buster/updates main' >> /etc/apt/sources.list || true && \
		apt-get update -o Acquire::Check-Valid-Until=false && \
		apt-get install -y curl git unzip && \
		curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
		composer update --no-interaction && \
		./vendor/bin/phpunit"