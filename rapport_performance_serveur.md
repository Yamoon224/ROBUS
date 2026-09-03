Voici&#x20;



THRESHOLDS
```bash
http_req_duration
✓ 'p(95)<1200' p(95)=791.78ms
✓ 'p(99)<2500' p(99)=1.91s

http_req_failed
✓ 'rate<0.02' rate=0.00%

safe_status
✓ 'rate>0.98' rate=100.00%

server_errors
✓ 'count<1' count=0
```

█ TOTAL RESULTS
```yaml
checks_total.......: 1872   15.570262/s
checks_succeeded...: 99.73% 1867 out of 1872
checks_failed......: 0.26%  5 out of 1872

✓ api health: status < 500
✓ api health: response time < 2500ms
✓ swagger docs-json: status < 500
✗ swagger docs-json: response time < 2500ms
  ↳  95% — ✓ 112 / ✗ 5
✓ GET /api/v1/fraya_taxi/rides/maps?page=1&limit=20: status < 500
✓ GET /api/v1/fraya_taxi/rides/maps?page=1&limit=20: response time < 2500ms
✓ GET /api/v1/fraya_taxi/rides/maps/in-progress?page=1&limit=20: status < 500
✓ GET /api/v1/fraya_taxi/rides/maps/in-progress?page=1&limit=20: response time < 2500ms
✓ GET /api/v1/fraya_taxi/rides/maps/admin?page=1&limit=20: status < 500
✓ GET /api/v1/fraya_taxi/rides/maps/admin?page=1&limit=20: response time < 2500ms
✓ GET /api/v1/fraya_taxi/rides/maps/heatmap: status < 500
✓ GET /api/v1/fraya_taxi/rides/maps/heatmap: response time < 2500ms
✓ GET /api/v1/fraya_taxi/rides/maps/drivers/nearby?onlineOnly=true: status < 500
✓ GET /api/v1/fraya_taxi/rides/maps/drivers/nearby?onlineOnly=true: response time < 2500ms
✓ GET /api/v1/fraya_taxi/rides/maps/monitoring/available-drivers: status < 500
✓ GET /api/v1/fraya_taxi/rides/maps/monitoring/available-drivers: response time < 2500ms

CUSTOM
checked_response_duration......: avg=240.405201 min=102.25859 med=122.104273 max=3565.983635 p(90)=543.008081 p(95)=791.787281
safe_status....................: 100.00% 936 out of 936
server_errors..................: 0       0/s

HTTP
http_req_duration..............: avg=240.4ms    min=102.25ms  med=122.1ms    max=3.56s       p(90)=543ms      p(95)=791.78ms  
  { expected_response:true }...: avg=240.4ms    min=102.25ms  med=122.1ms    max=3.56s       p(90)=543ms      p(95)=791.78ms  
http_req_failed................: 0.00%   0 out of 936
http_reqs......................: 936     7.785131/s

EXECUTION
iteration_duration.............: avg=4.03s      min=3.48s     med=3.8s       max=7.89s       p(90)=4.96s      p(95)=5.59s     
iterations.....................: 117     0.973141/s
vus............................: 1       min=1          max=5
vus_max........................: 5       min=5          max=5

NETWORK
data_received..................: 46 MB   382 kB/s
data_sent......................: 714 kB  5.9 kB/s
```



running (2m00.2s), 0/5 VUs, 117 complete and 0 interrupted iterations
default ✓ [======================================] 0/5 VUs  2m0s






Voici le resultat





THRESHOLDS
```bash
http_req_duration
✓ 'p(95)<1200' p(95)=860.12ms
✓ 'p(99)<2500' p(99)=1.4s

http_req_failed
✓ 'rate<0.02' rate=0.00%

safe_status
✓ 'rate>0.98' rate=100.00%

server_errors
✓ 'count<1' count=0
```

█ TOTAL RESULTS
```yaml
checks_total.......: 1120   9.240269/s
checks_succeeded...: 99.73% 1117 out of 1120
checks_failed......: 0.26%  3 out of 1120

✓ api health: status < 500
✓ api health: response time < 2500ms
✓ swagger docs-json: status < 500
✗ swagger docs-json: response time < 2500ms
  ↳  98% — ✓ 277 / ✗ 3

CUSTOM
checked_response_duration......: avg=399.668058 min=102.663384 med=428.305904 max=2854.086865 p(90)=744.605823 p(95)=860.128765
safe_status....................: 100.00% 560 out of 560
server_errors..................: 0       0/s

HTTP
http_req_duration..............: avg=399.66ms   min=102.66ms   med=428.3ms    max=2.85s       p(90)=744.6ms    p(95)=860.12ms  
  { expected_response:true }...: avg=399.66ms   min=102.66ms   med=428.3ms    max=2.85s       p(90)=744.6ms    p(95)=860.12ms  
http_req_failed................: 0.00%   0 out of 560
http_reqs......................: 560     4.620135/s

EXECUTION
iteration_duration.............: avg=1.68s      min=1.42s      med=1.56s      max=3.85s       p(90)=1.88s      p(95)=2.09s     
iterations.....................: 280     2.310067/s
vus............................: 1       min=1          max=5
vus_max........................: 5       min=5          max=5

NETWORK
data_received..................: 85 MB   700 kB/s
data_sent......................: 408 kB  3.4 kB/s
```



running (2m01.2s), 0/5 VUs, 280 complete and 0 interrupted iterations
default ✓ [======================================] 0/5 VUs  2m0s