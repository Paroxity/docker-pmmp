#!/bin/sh

if [ -z "$1" ]; then
	echo "Tag to build against must be provided!"
	exit 1
else
	TAG=$1
fi

set -e

if [ -z "${INPUT_USERNAME}" ]; then
  echo "Username is empty. Please set with.username to login to docker registry."
fi

if [ -z "${INPUT_PASSWORD}" ]; then
  echo "Password is empty. Please set with.password to login to docker registry."
fi

echo "${INPUT_PASSWORD}" | docker login -u ${INPUT_USERNAME} --password-stdin ${INPUT_REGISTRY}

export DOCKER_BUILDKIT=1

# check if we should pull existing images to help speed up the build
if [ "${INPUT_PULL}" == "true" ]; then
#  sh -c "docker pull paroxity/pmmp:'$TAG'"
	sh -c "docker pull paroxity/pmmp-phpstan:'$TAG'"
fi

# build pmmp image
#sh -c "cd pmmp && docker build --cache-from paroxity/pmmp:'$TAG' -t paroxity/pmmp:'$TAG' --build-arg PMMP_TAG='$TAG' --build-arg BUILDKIT_INLINE_CACHE=1 ."

# publish pmmp image
#sh -c "docker push paroxity/pmmp:'$TAG'"

# build phpstan image
sh -c "cd phpstan && docker build --cache-from paroxity/pmmp-phpstan:'$TAG' -t paroxity/pmmp-phpstan:'$TAG' --build-arg TAG='$TAG' --build-arg BUILDKIT_INLINE_CACHE=1 ."

# publish phpstan image
sh -c "docker push paroxity/pmmp-phpstan:'$TAG'"