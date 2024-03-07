ARG IMAGE
ARG TAG
#FROM ${IMAGE:-paroxity/pmmp}:${TAG}
FROM ${IMAGE:-pmmp/pocketmine-mp}:${TAG}

USER root
RUN wget -qO - https://getcomposer.org/installer | php
RUN mv composer.phar /usr/bin/composer
RUN wget -qO /usr/bin/phpstan https://github.com/phpstan/phpstan/releases/download/1.10.59/phpstan.phar
RUN chmod o+x /usr/bin/phpstan
ADD analyze.php /usr/bin/analyze
RUN chmod 755 /usr/bin/analyze
ADD phpstan.neon /pocketmine/phpstan.neon
ADD pocketmine.phpstan.neon /pocketmine/pocketmine.phpstan.neon
RUN mkdir /deps
RUN chown 1000:1000 /pocketmine/phpstan.neon /pocketmine/pocketmine.phpstan.neon /deps -R

# install custom rules
RUN mkdir -p /pocketmine/phpstan/rules
RUN wget -qO /pocketmine/phpstan/rules/DisallowEnumComparisonRule.php https://raw.githubusercontent.com/pmmp/PocketMine-MP/6846f1e78a70393d5f2d2e9f3db5c4b574ddf50d/tests/phpstan/rules/DisallowEnumComparisonRule.php
RUN wget -qO /pocketmine/phpstan/rules/UnsafeForeachArrayOfStringRule.php https://raw.githubusercontent.com/pmmp/PocketMine-MP/55c744cc006f290cdd4a71e82af3825f4328f43b/tests/phpstan/rules/UnsafeForeachArrayOfStringRule.php
ADD config/pm4-enum.phpstan.neon /pocketmine/phpstan/rules/pm4-enums.phpstan.neon
RUN chown 1000:1000 \
	/pocketmine/phpstan/rules/DisallowEnumComparisonRule.php \
	/pocketmine/phpstan/rules/pm4-enums.phpstan.neon

ENV PHPSTAN_CONFIG /pocketmine/phpstan.neon
ENTRYPOINT ["analyze"]
CMD []
