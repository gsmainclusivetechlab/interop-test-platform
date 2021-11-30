#!/bin/sh
base="${0%/*}"

# Production Dockerfile
cat "$base/parts/composer.Dockerfile" > "$base/production.Dockerfile"
cat "$base/parts/frontend.Dockerfile" >> "$base/production.Dockerfile"
cat "$base/parts/execution_environment.Dockerfile" >> "$base/production.Dockerfile"
cat "$base/parts/prod.Dockerfile" >> "$base/production.Dockerfile"

# Development Dockerfile
cat "$base/parts/execution_environment.Dockerfile" > "$base/development.Dockerfile"
cat "$base/parts/dev.Dockerfile" >> "$base/development.Dockerfile"
