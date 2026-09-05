# Echo

Echo is an ongoing real-time messaging application built with **Laravel** and **Next.js**.

The project is being developed with a focus on building a production-oriented messaging system with a clean API architecture, explicit authorization, real-time communication, background processing, and a separated frontend/backend architecture.

---

## Tech Stack

### Backend

* **Laravel 13**
* **PHP**
* **Laravel Passport** — API authentication
* **Laravel Reverb** — WebSocket server
* **Redis** — queues, cache, and real-time infrastructure
* **Laravel Horizon** — queue monitoring and processing
* **MySQL** — relational database
* **Nginx** — web server / reverse proxy

### Frontend

* **Next.js 16**
* JavaScript / TypeScript
* Laravel API integration
* Real-time communication using the Pusher-compatible broadcasting protocol

### Infrastructure

The development environment is containerized using Docker.

```text
Docker
├── nginx
├── frontend
├── backend
├── mysql
├── redis
├── horizon
└── reverb
```

---

# Architecture

Echo uses a separated frontend/backend architecture.

```text
                         ┌──────────────────┐
                         │     Next.js      │
                         │     Frontend     │
                         └────────┬─────────┘
                                  │
                     HTTP / API   │
                                  ▼
                         ┌──────────────────┐
                         │      Nginx       │
                         └────────┬─────────┘
                                  │
                                  ▼
                         ┌──────────────────┐
                         │     Laravel      │
                         │      API         │
                         └───────┬───┬──────┘
                                 │   │
                    ┌────────────┘   └──────────────┐
                    ▼                               ▼
             ┌─────────────┐                 ┌─────────────┐
             │    MySQL    │                 │    Redis    │
             └─────────────┘                 └──────┬──────┘
                                                    │
                                     ┌──────────────┴──────────────┐
                                     ▼                             ▼
                              ┌─────────────┐               ┌─────────────┐
                              │   Horizon   │               │   Reverb    │
                              │ Queue Jobs  │               │ WebSockets  │
                              └─────────────┘               └─────────────┘
```

The backend is responsible for:

* Authentication
* Authorization
* Conversations
* Participants
* Messages
* Reactions
* Database operations
* Events
* Broadcasting

The Next.js application is responsible for the client-side application and communicates with Laravel through the API.

---

# Docker Development Environment

Echo uses Docker to provide a consistent development environment.

The Docker Compose configuration contains the application's main services:

```text
┌───────────────────────────────────────────────────┐
│                     Docker                         │
│                                                   │
│  ┌─────────┐       ┌──────────┐                   │
│  │  Nginx  │──────▶│ Backend  │                   │
│  └─────────┘       └────┬─────┘                   │
│                         │                         │
│          ┌──────────────┼──────────────┐          │
│          ▼              ▼              ▼          │
│       ┌──────┐       ┌───────┐     ┌────────┐    │
│       │ MySQL│       │ Redis │     │ Reverb │    │
│       └──────┘       └───┬───┘     └────────┘    │
│                           │                       │
│                           ▼                       │
│                       ┌────────┐                  │
│                       │Horizon │                  │
│                       └────────┘                  │
│                                                   │
│  ┌─────────────────────────────────────────────┐  │
│  │                  Frontend                   │  │
│  │                  Next.js                    │  │
│  └─────────────────────────────────────────────┘  │
└───────────────────────────────────────────────────┘
```

## Docker Services

### Nginx

Nginx acts as the web server/reverse proxy and provides the entry point to the application.

### Backend

The backend container runs the Laravel application and handles the HTTP API.

### Frontend

The frontend container runs the Next.js application.

### MySQL

MySQL stores the application's persistent relational data.

### Redis

Redis is used as infrastructure for:

* Queue processing
* Cache
* Broadcasting-related communication

### Horizon

Laravel Horizon processes and monitors Laravel's queued jobs.

This is important because broadcasting and other asynchronous operations can be handled through Laravel's queue system instead of blocking the HTTP request.

### Reverb

Laravel Reverb provides the WebSocket server used for real-time communication.

Reverb uses the Pusher-compatible WebSocket protocol, allowing the frontend to communicate with Laravel's broadcasting system through a standard broadcasting client.

---

# Installation

## Requirements

You only need the development prerequisites on the host machine:

* Git
* Docker
* Docker Compose

PHP, Composer, Node.js, MySQL, and Redis do not need to be installed separately when using the Docker development environment.

---

## Clone the Repository

```bash
git clone <repository-url>

cd echo
```

---

## Configure Environment

Create the Laravel environment file:

```bash
cp .env.example .env
```

Configure the environment according to the Docker Compose services.

The database host must refer to the Docker service rather than `localhost`.

For example:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=echo
DB_USERNAME=...
DB_PASSWORD=...
```

Similarly, Redis configuration should use the Redis Docker service:

```env
REDIS_HOST=redis
```

The exact values should match the project's `.env.example` and Docker Compose configuration.

---

# Start the Application

Build and start the containers:

```bash
docker compose up -d --build
```

Check the running services:

```bash
docker compose ps
```

View all logs:

```bash
docker compose logs -f
```

View logs for a particular service:

```bash
docker compose logs -f backend
```

---

# Laravel Setup

Install Composer dependencies:

```bash
docker compose exec backend composer install
```

Generate the Laravel application key:

```bash
docker compose exec backend php artisan key:generate
```

Run migrations:

```bash
docker compose exec backend php artisan migrate
```

If seeders are required:

```bash
docker compose exec backend php artisan migrate --seed
```

---

# Laravel Passport

Echo uses **Laravel Passport** for API authentication.

Passport provides the OAuth2-based authentication layer between the Next.js frontend and Laravel API.

The general authentication architecture is:

```text
Next.js
   │
   │ Authentication
   ▼
Laravel Passport
   │
   ▼
Authenticated API Request
   │
   ▼
Laravel
```

Passport is also used when authenticating access to protected broadcasting channels.

Generate Passport keys when required:

```bash
docker compose exec backend php artisan passport:keys
```

The exact Passport installation/setup commands depend on the state of the application's database and Passport configuration.

---

# Next.js Frontend

The frontend is a separate **Next.js 16** application.

Echo therefore consists of two primary application layers:

```text
┌────────────────────────┐
│      Next.js 16        │
│       Frontend         │
└───────────┬────────────┘
            │
            │ HTTP
            │
            ▼
┌────────────────────────┐
│      Laravel 13        │
│        API             │
└────────────────────────┘
```

The frontend does not directly access the database.

All application data is obtained through the Laravel API.

---

# Authentication Flow

Laravel Passport is responsible for authenticating API requests from the Next.js frontend.

The general flow is:

```text
Next.js
   │
   │ Login / Authentication
   ▼
Laravel API
   │
   ▼
Passport
   │
   ▼
Authentication Credentials
   │
   ▼
Next.js
   │
   │ Authenticated API requests
   ▼
Laravel
```

The backend remains responsible for determining whether an authenticated user is authorized to perform an operation.

Authentication and authorization are therefore separate concerns:

```text
Authentication
    │
    └── Who is the user?

Authorization
    │
    └── What is the user allowed to do?
```

Laravel Passport handles the first concern, while Laravel Policies and Gates handle the second.

---

# Passport and Broadcasting Authentication

Echo also uses Passport authentication when a client attempts to subscribe to protected broadcast channels.

Private and presence-style channels cannot simply be subscribed to by anyone who knows the channel name.

The client first requests authorization from Laravel.

The flow is approximately:

```text
Next.js
   │
   │ Subscribe to private channel
   ▼
/broadcasting/auth
   │
   │ Authentication credentials
   ▼
Laravel
   │
   ▼
Passport Authentication
   │
   ▼
Channel Authorization
   │
   ├── Allowed
   │
   └── Denied
```

The broadcasting authentication endpoint uses the authenticated API guard.

The project also contains the required cookie-to-Bearer handling so that authentication originating from the frontend can be recognized correctly by Laravel's API authentication layer.

This allows the same authenticated user identity to be used for both:

* API requests
* Private broadcast channel authorization

---

# Conversations

A conversation represents a messaging space between users.

Users are connected to conversations through conversation participants.

The conversation domain therefore consists primarily of:

```text
Conversation
     │
     └── Participants
             │
             └── Users
```

Conversations provide the context in which messages and other activity occur.

---

# Conversation Participants

A conversation has participants, and participants have roles.

The participant relationship is represented separately from the user and conversation models so that conversation-specific information can be stored.

Conceptually:

```text
User
 │
 │
 ▼
ConversationParticipant
 │
 ├── conversation_id
 ├── user_id
 └── role
 │
 ▼
Conversation
```

This allows a user to have a different role in different conversations.

---

# Participant Roles

Participant roles are used when determining permissions within a conversation.

The participant listing also uses custom ordering so that higher-priority roles, such as the owner, can be returned before regular participants.

The role is therefore both:

* Domain information
* An input into authorization/business rules

---

# Listing Participants

Conversation participants are retrieved through a dedicated Action:

```text
ListConversationParticipants
```

The Action returns a cursor paginator.

Conceptually:

```php
public function handle(Conversation $conversation): CursorPaginator
{
    // Retrieve participants
}
```

This keeps the participant query outside the controller.

---

# Messages

Messages belong to conversations and are created by users.

The relationship is:

```text
User
  │
  └── Messages

Conversation
  │
  └── Messages
```

A message therefore has both a conversation context and an author.

---

# Message API

Messages are accessed through their conversation context when listing or creating messages.

Conceptually:

```text
/conversations/{conversation}/messages
```

Individual messages can then be addressed directly:

```text
/messages/{message}
```

This gives the API a clear distinction between:

* Operations on a collection of messages belonging to a conversation
* Operations on an individual message

---

# Message Listing

Messages are retrieved using cursor pagination.

This is particularly useful for a messaging application because conversations can contain a continuously growing number of messages.

Instead of:

```text
page=1
page=2
page=3
```

the API uses a cursor:

```text
Initial request
      │
      ▼
Messages
      │
      ▼
Next Cursor
      │
      ▼
More Messages
```

This avoids the performance and consistency problems that can occur with large offset-based message queries.

---

# Message Authorization

Message authorization is handled through `MessagePolicy`.

The policy distinguishes between collection-level and individual-resource authorization.

For listing messages in a conversation:

```php
Gate::authorize('viewAny', [Message::class, $conversation]);
```

Here:

* `Message::class` identifies the resource being authorized.
* `$conversation` provides the context for the collection.

For an individual conversation:

```php
Gate::authorize('view', $conversation);
```

There is no need to pass `Conversation::class` because `$conversation` is already the resource being authorized.

---

# Message Policy Operations

The Message Policy can define operations such as:

```text
viewAny
view
create
update
delete
restore
forceDelete
```

The policy determines access based on the relationship between:

* The authenticated user
* The conversation
* The message

This keeps authorization rules centralized.

---

# Message Creation

Message creation uses a dedicated Form Request:

```text
StoreMessageRequest
```

The request handles incoming validation and request authorization.

The application flow is:

```text
HTTP Request
     │
     ▼
StoreMessageRequest
     │
     ├── Validate input
     └── Authorize request
     │
     ▼
Message Action
     │
     ▼
Message Model
     │
     ▼
Database
     │
     ▼
MessageCreated
```

This prevents the controller from becoming responsible for validation, authorization, persistence, and event handling all at once.

---

# Reactions

Messages can have reactions.

A reaction belongs to:

* A message
* A user

Conceptually:

```text
Message
   │
   └── Reactions
          │
          └── User
```

This allows the application to determine which user created each reaction.

---

# Reaction Creation

Reaction creation uses:

```text
StoreReactionRequest
```

The request is responsible for validating the reaction input and authorizing the operation.

The application can then create the reaction through the appropriate message/user relationships.

---

# Reaction Authorization

Reaction deletion is based on ownership.

The policy rule is:

```php
public function delete(User $user, Reaction $reaction): bool
{
    return $reaction->user_id === $user->id;
}
```

Therefore:

```text
User A
  │
  └── Reaction A → Can delete

User B
  │
  └── Reaction A → Cannot delete
```

A user is not allowed to remove another user's reaction.

---

# Actions

Echo uses dedicated Action classes for application operations.

Actions keep business logic out of controllers.

The general structure is:

```text
Controller
    │
    ▼
Action
    │
    ▼
Model / Relationship
    │
    ▼
Database
```

An Action should represent a meaningful application operation rather than becoming a generic utility class.

Examples include:

```text
ListConversationParticipants
```

and Actions responsible for conversation, message, and reaction operations.

---

# Form Requests

Form Requests provide the boundary between incoming HTTP data and the application's business logic.

They handle:

* Validation
* Request authorization

Examples:

```text
StoreMessageRequest
StoreReactionRequest
```

The intended responsibility separation is:

```text
Form Request
    ├── Validation
    └── Authorization

Controller
    └── HTTP coordination

Action
    └── Business operation
```

---

# Policies and Gates

Echo uses Laravel Policies and Gates for authorization.

Authorization is intentionally kept outside controllers wherever possible.

For example:

```php
Gate::authorize('viewAny', [Message::class, $conversation]);
```

This allows the same authorization rules to be reused by different entry points.

Authorization is especially important for messaging because both the HTTP API and real-time broadcasting must respect conversation membership.

---

# API Resources

Laravel API Resources are used to control how models are exposed through the API.

The Resource layer acts as the boundary between the application's internal Eloquent models and the public JSON representation.

```text
Eloquent Model
      │
      ▼
API Resource
      │
      ▼
JSON Response
```

This prevents the API contract from becoming unnecessarily coupled to the internal database structure.

---

# Real-Time Messaging

Real-time functionality is one of the central parts of Echo.

Laravel events are used to represent changes, and Laravel Broadcasting delivers those changes to connected clients.

The architecture is:

```text
Database Change
      │
      ▼
Laravel Event
      │
      ▼
Queue / Broadcasting
      │
      ▼
Redis
      │
      ▼
Reverb
      │
      ▼
WebSocket
      │
      ▼
Next.js Client
```

---

# Laravel Reverb

Echo uses **Laravel Reverb** as the WebSocket server.

Reverb provides the persistent WebSocket connection between Laravel and the frontend.

The frontend connects to Reverb using the Pusher-compatible broadcasting protocol.

Conceptually:

```text
Next.js
   │
   │ WebSocket
   ▼
Reverb
   │
   ▼
Laravel Broadcasting
```

Reverb runs as its own long-running Docker service.

The Reverb container runs:

```bash
php artisan reverb:start
```

This keeps the WebSocket server independent from the normal Laravel HTTP process.

---

# Redis and Queues

Redis is used as part of Echo's asynchronous infrastructure.

Laravel queued jobs can be processed separately from normal HTTP requests.

The architecture is:

```text
Laravel
   │
   ▼
Redis Queue
   │
   ▼
Horizon
   │
   ▼
Background Job
```

This allows long-running or asynchronous operations to be processed without blocking API requests.

---

# Laravel Horizon

Horizon manages Laravel's Redis-backed queues.

Horizon runs as a separate long-running Docker service.

Its responsibilities include:

* Processing queued jobs
* Monitoring queues
* Providing visibility into queue activity

This is particularly useful for a real-time application where events and other operations may be queued.

---

# Broadcasting Events

Echo uses dedicated events for real-time updates.

Important events include:

```text
MessageCreated
MessageUpdated
ConversationActivityUpdated
```

These events allow the Next.js frontend to update its local state without requesting the entire conversation again.

---

# MessageCreated

When a message is successfully created, the application dispatches a `MessageCreated` event.

The event is broadcast to the relevant conversation.

```text
Create Message
     │
     ▼
Database
     │
     ▼
MessageCreated
     │
     ▼
Conversation Broadcast Channel
     │
     ▼
Next.js Clients
```

Clients subscribed to that conversation can then add the new message to their local state.

---

# MessageUpdated

When an existing message changes, `MessageUpdated` can be broadcast to the relevant conversation.

The frontend can use the event to update the existing message rather than reloading the entire message list.

```text
Update Message
      │
      ▼
MessageUpdated
      │
      ▼
Conversation Channel
      │
      ▼
Next.js
```

---

# ConversationActivityUpdated

Conversation-level activity is handled separately from individual message events.

`ConversationActivityUpdated` allows clients to be notified when the activity state of a conversation changes.

This provides a mechanism for updating conversation-level information without requiring the client to infer every change from individual messages.

---

# Broadcast Channels

Conversation broadcasting is protected.

A user should only be able to subscribe to a conversation's private channel if they are a participant in that conversation.

The authorization flow is:

```text
Client
  │
  │ Subscribe
  ▼
Private Conversation Channel
  │
  ▼
/broadcasting/auth
  │
  ▼
Authenticate User
  │
  ▼
Check Conversation Membership
  │
  ├──────────────┐
  ▼              ▼
Allowed        Denied
  │
  ▼
WebSocket Subscription
```

This ensures that knowing a conversation channel name is not enough to receive its messages.

---

# API and Real-Time Authorization

The HTTP API and broadcasting system follow the same security principle:

> A user must have access to the conversation before accessing its protected messaging data or real-time activity.

For the API, this is handled through Policies and Gates.

For broadcasting, this is handled through broadcast channel authorization.

```text
                 User
                  │
          ┌───────┴────────┐
          ▼                ▼
     Laravel API       Broadcasting
          │                │
          ▼                ▼
      Policies        Channel Auth
          │                │
          └───────┬────────┘
                  ▼
          Conversation Access
```

---

# After-Commit Event Dispatching

Real-time events should represent data that has successfully been committed to the database.

For database-dependent events, Echo uses after-commit behavior so that an event is not broadcast before its transaction has successfully completed.

The desired sequence is:

```text
Begin Transaction
       │
       ▼
Create / Update Data
       │
       ▼
Commit
       │
       ▼
Dispatch Event
       │
       ▼
Broadcast
       │
       ▼
Clients
```

This avoids situations where a client receives an event for data that was subsequently rolled back.

---

# Relationship Usage

Echo uses Eloquent relationships throughout the domain.

Examples include:

```php
$message->conversation()
$message->user()
$conversation->participants()
```

There is an important distinction between relationship methods and relationship properties.

### Relationship Method

Use the relationship method when operating on the relationship itself:

```php
$conversation->participants()->create([...]);
```

or:

```php
$message->conversation()->associate($conversation);
```

The method returns the relationship object and allows querying or modifying the relationship.

### Relationship Property

Use the relationship property when accessing the related model:

```php
$message->user;
```

This retrieves the related model rather than the relationship builder.

---

# API Structure

The API is organized around the application's domain resources.

A simplified structure is:

```text
/conversations
    │
    ├── GET
    ├── POST
    │
    └── /{conversation}
            │
            ├── PATCH
            ├── DELETE
            │
            ├── /participants
            │
            └── /messages
                    │
                    ├── GET
                    └── POST

/messages/{message}
    │
    ├── GET
    ├── PATCH
    └── DELETE

/messages/{message}/reactions
    │
    └── POST

/reactions/{reaction}
    │
    └── DELETE
```

The actual route definitions in the Laravel application remain the source of truth.

---

# Project Structure

The application follows a separation between HTTP handling, business operations, persistence, authorization, and real-time events.

A simplified structure is:

```text
app/
├── Actions/
│   ├── Conversation/
│   ├── ConversationParticipant/
│   ├── Message/
│   └── Reaction/
│
├── Events/
│   ├── MessageCreated.php
│   ├── MessageUpdated.php
│   └── ConversationActivityUpdated.php
│
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   │   ├── StoreMessageRequest.php
│   │   └── StoreReactionRequest.php
│   │
│   └── Resources/
│
├── Models/
│   ├── Conversation.php
│   ├── ConversationParticipant.php
│   ├── Message.php
│   └── Reaction.php
│
└── Policies/
    ├── ConversationPolicy.php
    ├── MessagePolicy.php
    └── ReactionPolicy.php
```

The exact project structure can evolve as new functionality is introduced.

---

# Common Docker Commands

## Start

```bash
docker compose up -d
```

## Start and rebuild

```bash
docker compose up -d --build
```

## Stop

```bash
docker compose down
```

## Restart

```bash
docker compose restart
```

## Check services

```bash
docker compose ps
```

## View logs

```bash
docker compose logs -f
```

## View a specific service

```bash
docker compose logs -f backend
```

## Open a shell in the backend

```bash
docker compose exec backend bash
```

If the container uses `sh`:

```bash
docker compose exec backend sh
```

---

# Common Laravel Commands

Run Artisan commands through the backend container:

```bash
docker compose exec backend php artisan <command>
```

Examples:

```bash
docker compose exec backend php artisan migrate
```

```bash
docker compose exec backend php artisan route:list
```

```bash
docker compose exec backend php artisan tinker
```

```bash
docker compose exec backend php artisan optimize:clear
```

---

# Composer Commands

Install dependencies:

```bash
docker compose exec backend composer install
```

Add a package:

```bash
docker compose exec backend composer require <package>
```

Remove a package:

```bash
docker compose exec backend composer remove <package>
```

---

# Database Commands

Run migrations:

```bash
docker compose exec backend php artisan migrate
```

Rollback migrations:

```bash
docker compose exec backend php artisan migrate:rollback
```

Fresh migration:

```bash
docker compose exec backend php artisan migrate:fresh
```

Fresh migration with seed data:

```bash
docker compose exec backend php artisan migrate:fresh --seed
```

---

# Clearing Laravel Cache

Clear Laravel's cached application state:

```bash
docker compose exec backend php artisan optimize:clear
```

This is useful after changing configuration, routes, events, or other cached application information.

---

# Frontend Development

The Next.js frontend runs independently from the Laravel API.

A typical development flow is:

```text
1. Start Docker services
        │
        ▼
2. Laravel API starts
        │
        ├── MySQL
        ├── Redis
        ├── Horizon
        └── Reverb
        │
        ▼
3. Next.js starts
        │
        ▼
4. Next.js communicates with Laravel
        │
        ├── API requests
        └── WebSocket connection
```

The frontend communicates with Laravel for application data and uses Reverb for real-time updates.

---

# Complete Development Flow

The complete Echo architecture can be summarized as:

```text
                         NEXT.JS
                           │
              ┌────────────┴────────────┐
              │                         │
          HTTP API                  WebSocket
              │                         │
              ▼                         ▼
         Laravel API                 Reverb
              │                         ▲
       ┌──────┼───────┐               │
       │      │       │               │
       ▼      ▼       ▼               │
   Passport Policies Actions          │
       │      │       │               │
       └──────┴───────┘               │
              │                       │
              ▼                       │
           Eloquent                   │
              │                       │
              ▼                       │
            MySQL                     │
                                      │
       Events ──▶ Redis ──▶ Horizon ──┘
```

This architecture separates:

* Frontend presentation
* API communication
* Authentication
* Authorization
* Business logic
* Persistence
* Queue processing
* Real-time communication

---

# Design Principles

Echo is being developed around several core principles.

## Thin Controllers

Controllers should coordinate HTTP requests rather than contain the application's business logic.

## Dedicated Actions

Business operations are isolated into dedicated Action classes.

## Explicit Authorization

Policies and Gates define who can perform operations.

## Request Validation

Form Requests handle validation and request-level authorization.

## Conversation-Scoped Access

Messages and real-time communication are protected by conversation membership.

## Cursor Pagination

Cursor pagination is used for collections where data can grow continuously, particularly messaging-related data.

## Event-Driven Real-Time Communication

Database/application changes are represented by events and delivered to clients through broadcasting.

## Asynchronous Processing

Redis and Horizon provide infrastructure for processing queued operations without blocking normal API requests.

## Separation of Frontend and Backend

Next.js handles the frontend while Laravel remains responsible for the application's API, authentication, authorization, persistence, and domain logic.

---

# Current Development Status

Echo currently focuses on establishing the core infrastructure required for a real-time messaging application:

* Laravel API
* Next.js frontend
* Docker development environment
* Laravel Passport authentication
* Conversation management
* Conversation participants
* Participant roles
* Message management
* Message authorization
* Message reactions
* Reaction authorization
* Form Requests
* Action classes
* API Resources
* Cursor pagination
* Laravel Events
* Protected broadcast channels
* Laravel Reverb
* Redis
* Laravel Horizon
* Real-time message events
* Conversation activity events
* After-commit event handling

The architecture is designed so that additional messaging functionality can be introduced without tightly coupling the frontend, controllers, models, authorization, and real-time infrastructure.

---

# Future Development

As Echo continues to evolve, additional functionality can be added around the existing architecture.

Potential areas include:

* Read receipts
* Typing indicators
* Online/offline presence
* Message attachments
* Message replies
* Message search
* Notifications
* Additional conversation functionality
* More granular participant permissions
* Additional real-time events
* Improved testing coverage
* Production deployment configuration

These items represent potential future development and should not be considered implemented unless they are present in the codebase.

---

# Project Status

**Echo is an ongoing project.**

The architecture and feature set are actively evolving as new messaging, authentication, real-time communication, and infrastructure capabilities are implemented.

The current goal is to continue developing Echo into a robust, maintainable, and production-ready real-time messaging platform while keeping the separation between the frontend, backend, authentication, authorization, persistence, background processing, and real-time communication clear.
