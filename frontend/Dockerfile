# ============================================
# Stage 1: Dependencies Installation Stage
# ============================================

# IMPORTANT: Docker Hardened Image (DHI) Version Maintenance
# This Dockerfile uses dhi.io/node. Regularly validate and update to the latest DHI versions in the catalog for security and compatibility.

FROM dhi.io/node:24.15.0-alpine3.23-dev AS dependencies
# Set Working Directory
WORKDIR /app
# Copy Package related files to Leverage Docker Caching for Dependencies
COPY package.json yarn.lock* package-lock.json* pnpm-lock.yaml* .npmrc* ./

# Install Project Dependencies with frozen-lockfile to ensure consistency
RUN --mount=type=cache,target=/root/.npm \
    --mount=type=cache,target=/usr/local/share/.cache/yarn \
    --mount=type=cache,target=/root/.local/share/pnpm/store \
    if [ -f package-lock.json ]; then \
        npm ci --no-audit --no-fund; \
    elif [ -f yarn.lock ]; then \
        corepack enable yarn && yarn install --frozen-lockfile --production=false; \
    elif [ -f pnpm-lock.yaml ]; then \
        corepack enable pnpm && pnpm install --frozen-lockfile; \
    else \
        echo "No Lockfile found." && exit 1; \
    fi

# ============================================
# Stage 2: Build Next.js application in standalone mode
# ============================================
FROM dhi.io/node:24.15.0-alpine3.23-dev AS builder
# Set Working Directory
WORKDIR /app
# Copy Project Dependencies from the Previous Stage
COPY --from=dependencies /app/node_modules ./node_modules
# Copy Application Source Code
COPY . .
# Disable Next.js Telemetry for CI/CD Environments
ENV NEXT_TELEMETRY_DISABLED=1
# Build the Next.js Application in Standalone Mode
RUN if [ -f package-lock.json ]; then \
        npm run build; \
    elif [ -f yarn.lock ]; then \
        corepack enable yarn && yarn build; \
    elif [ -f pnpm-lock.yaml ]; then \
        corepack enable pnpm && pnpm build; \
    else \
        echo "No Lockfile found." && exit 1; \
    fi

# ============================================
# Stage 3: Run Next.js application
# ============================================
FROM dhi.io/node:24.15.0-alpine3.23-dev AS runner
# Set Working Directory
WORKDIR /app
# Set Production Environment Variable
ENV NODE_ENV=production
ENV PORT=3000
ENV HOSTNAME="0.0.0.0"
ENV NEXT_TELEMETRY_DISABLED=1

# Copy Production Assets from the Builder Stage
COPY --from=builder --chown=node:node /app/public ./public
# Create .next directory and set necessary permissions
RUN mkdir -p .next && chown node:node .next
# Copy the Standalone Build Output
COPY --from=builder --chown=node:node /app/.next/standalone ./
COPY --from=builder --chown=node:node /app/.next/static .next/static
# Switch to Non-Root User for Security Best Practices
USER node
# Expose the Application Port
EXPOSE 3000
# Start the Next.js Application
CMD ["node", "server.js"]


