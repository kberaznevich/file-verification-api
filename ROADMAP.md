# Project Roadmap

#### This document outlines the improvements for the project. The roadmap provides a guide for future development efforts and highlights key areas for enhancement.

## 1. Implement Complex Authorization Logic

### Overview
#### Authorization is a critical component of the application, ensuring that users have the correct permissions to access various resources. To enhance security and flexibility, we plan to implement complex authorization logic where necessary.

### Tasks
#### **Middleware**: Develop additional middleware to handle complex authorization scenarios that cannot be easily managed by standard route protection.
#### **Policies**: Implement policies for fine-grained control over user actions on resources.
#### **Form Request Authorization**: Utilize the authorize method in form requests to enforce authorization checks before validation and controller logic are executed.


## 2. Implement Caching for DNS Request

### Overview
#### The application makes requests to https://dns.google/resolve?name=ropstore.accredify.io&type=TXT to retrieve DNS records. To improve performance and reduce the number of external requests, we will implement caching for these DNS requests.

### Tasks
#### **Cache Layer**: Integrate a caching layer to store responses from the DNS requests.
#### **Cache Expiration**: Set appropriate expiration times for cached DNS responses to ensure data freshness.
#### **Cache Invalidation**: Implement cache invalidation strategies in case DNS records are updated or changed.