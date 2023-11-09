#!/bin/sh

YELLOW_COLOR='\033[1;33m'
NO_COLOR='\033[0m'

echo "${YELLOW_COLOR}INSTALLING GIT-HOOKS${NO_COLOR}";

echo "${NO_COLOR}Install pre-commit...${NO_COLOR}";
ln -frns ./.hooks/pre-commit ./.git/hooks/pre-commit
ln -frns ./.hooks/pre-commit.d ./.git/hooks/pre-commit.d

echo "${NO_COLOR}Setting permissions...${NO_COLOR}";
sudo chmod 0777 .git/hooks/pre-commit
sudo chmod -R 0777 .git/hooks/pre-commit.d

echo "${NO_COLOR}Done.${NO_COLOR}";