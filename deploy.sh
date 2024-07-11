#!/bin/bash
echo "Lancement du script"
# Check out to the dev branch
git checkout stage
# Pull the latest changes from dev
git pull origin stage
# Check out to the main branch
git checkout main
# Merge the dev branch into main
git merge stage
# Push the changes to the main branch
#git push origin main
# Build and start the containers using Docker Compose
docker-compose -f docker-compose.yml up --build -d
echo "Deployment to production complete!"
