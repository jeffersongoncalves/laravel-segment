# Changelog

All notable changes to this project will be documented in this file.

## 1.0.0 - 2026-09-08

Initial release.

Laravel client for the Segment HTTP API, built on Laravel's `Http` client.

**Tracking API** — `track`, `identify`, `page`, `batch`
**Profile API (Personas)** — `profileTraits`, `profileEvents`

Credentials resolve from `config/segment.php` (`SEGMENT_WRITE_KEY`, `SEGMENT_ACCESS_TOKEN`, `SEGMENT_SPACE_ID`). Each call throws `InvalidArgumentException` when the credential it requires is missing.

Supports PHP 8.2+ and Laravel 12/13.

## [Unreleased]
