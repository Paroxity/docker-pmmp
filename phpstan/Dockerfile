ARG IMAGE
ARG TAG
FROM ${IMAGE:-paroxity/pmmp}:${TAG}

USER root
RUN curl -L https://getcomposer.org/installer | php
RUN mv composer.phar /usr/bin/composer
RUN curl -L https://github.com/phpstan/phpstan/releases/download/1.2.0/phpstan.phar -o /usr/bin/phpstan/phpstan.phar
RUN chmod o+x /usr/bin/phpstan
ADD analyze.php /usr/bin/analyze
RUN chmod 755 /usr/bin/analyze
ADD phpstan.neon /pocketmine/phpstan.neon
ADD pocketmine.phpstan.neon /pocketmine/pocketmine.phpstan.neon
RUN mkdir /deps
RUN chown 1000:1000 /pocketmine/phpstan.neon /pocketmine/pocketmine.phpstan.neon /deps -R

# install custom rules
RUN mkdir -p /pocketmine/phpstan/rules
RUN curl -L https://raw.githubusercontent.com/pmmp/PocketMine-MP/4014f9a4f2426b358288ce96386b8d73db760512/tests/phpstan/rules/DisallowEnumComparisonRule.php -o /pocketmine/phpstan/rules/DisallowEnumComparisonRule.php
ADD config/pm4-enum.phpstan.neon /pocketmine/phpstan/rules/pm4-enums.phpstan.neon
RUN chown 1000:1000 \
	/pocketmine/phpstan/rules/DisallowEnumComparisonRule.php \
	/pocketmine/phpstan/rules/pm4-enums.phpstan.neon

ENV PHPSTAN_CONFIG /pocketmine/phpstan.neon
ENTRYPOINT ["analyze"]
CMD []
