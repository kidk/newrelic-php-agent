<?php
/*
 * Copyright 2020 New Relic Corporation. All rights reserved.
 * SPDX-License-Identifier: Apache-2.0
 */

/*DESCRIPTION
The agent should *not* send code level metrics (CLM) when disabled.
 */

/*SKIPIF
<?php
if (version_compare(PHP_VERSION, "7.0", "<")) {
  die("skip: CLM for PHP 5 not supported\n");
}
*/

/*INI
newrelic.distributed_tracing_enabled=1
newrelic.span_events_enabled=1
newrelic.cross_application_tracer.enabled=false
newrelic.code_level_metrics.enabled=false
*/

/*EXPECT_TRACED_ERRORS
[
  "?? agent run id",
  [
    [
      "?? when",
      "OtherTransaction/php__FILE__",
      "I'M COVERED IN BEES!",
      "NoticedError",
      {
        "stack_trace": [
          " in newrelic_notice_error called at __FILE__ (??)"
        ],
        "agentAttributes": {},
        "intrinsics": "??"
      }
    ]
  ]
]
*/

/*EXPECT_ERROR_EVENTS
[
  "?? agent run id",
  {
    "reservoir_size": "??",
    "events_seen": 1
  },
  [
    [
      {
        "type": "TransactionError",
        "timestamp": "??",
        "error.class": "NoticedError",
        "error.message": "I'M COVERED IN BEES!",
        "transactionName": "OtherTransaction/php__FILE__",
        "duration": "??",
        "nr.transactionGuid": "??",
        "guid": "??",
        "sampled": true,
        "priority": "??",
        "traceId": "??",
        "spanId": "??"
      },
      {},
      {}
    ]
  ]
]
*/

/*EXPECT_ANALYTICS_EVENTS
 [
  "?? agent run id",
  "?? sampling information",
  [
    [
      {
        "type": "Transaction",
        "name": "OtherTransaction/php__FILE__",
        "timestamp": "??",
        "duration": "??",
        "totalTime": "??",
        "guid": "??",
        "sampled": true,
        "priority": "??",
        "traceId": "??",
        "error": true
      },
      {
      },
      {
        "errorType": "NoticedError",
        "errorMessage": "I'M COVERED IN BEES!"
      }
    ]
  ]
]
*/

/*EXPECT_SPAN_EVENTS
[
  "?? agent run id",
  {
    "reservoir_size": 10000,
    "events_seen": 1
  },
  [
    [
      {
        "traceId": "??",
        "duration": "??",
        "transactionId": "??",
        "name": "OtherTransaction\/php__FILE__",
        "guid": "??",
        "type": "Span",
        "category": "generic",
        "priority": "??",
        "sampled": true,
        "nr.entryPoint": true,
        "timestamp": "??",
        "transaction.name": "OtherTransaction\/php__FILE__"
      },
      {},
      {
        "error.class": "NoticedError",
        "error.message": "I'M COVERED IN BEES!"
      }
    ]
  ]
]
 */

header('Content-Type: application/pdf');
header('Content-Length: 867');
newrelic_notice_error("I'M COVERED IN BEES!");
