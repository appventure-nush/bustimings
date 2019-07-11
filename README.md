# bustimings [![Build Status](https://appventure.nushigh.edu.sg:8000/api/badges/appventure-nush/bustimings/status.svg)](https://appventure.nushigh.edu.sg:8000/appventure-nush/bustimings)

\[Deployment: [docker-compose.yml](https://github.com/appventure-nush/infrastructure/blob/master/setup-scripts/main-bustimings.yml) | [registry](https://appventure.nushigh.edu.sg/registry/#/bustimings)\]

The NUSH Bus Timings webpage is displayed on TV screens around the school to indicate bus arrival times. Real-time data is retrieved from the LTA API and shows the arrival times of the next 2 buses in the 4 bus stops around the school. The display is colour coded to show how occupied the buses are.

[4.php](4.php) is used for the normal displays around the school as the machines are running IE8
[5.php](5.php) is used for the interactive display

Authors:
- Wayne Tee 2017
- Xavier Oh 2019
