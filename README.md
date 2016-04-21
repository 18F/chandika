# Chandika

Chandika provides information on the resources being used by our services, allows new resources to be created and maintained, and destroys unused resources.

## Developing and testing locally

Chandika uses [Scotch Box](https://box.scotch.io/) as a testing environment. Check out the code, run `vagrant up`, and hit `192.168.33.20`.

## Deploying to cloud.gov

Once you've [set up your credentials on cloud.gov](https://docs.cloud.gov/getting-started/setup/) and [associated a mysql database](https://docs.cloud.gov/apps/managed-services/), simply

```
cd public
cf push [appname]
```

## The origin of the name Chandika

The demon Raktabija had a superpower that meant that when a drop of his blood hit the ground, a new duplicate Raktabija would be created. Thus when the goddess Kali fought him, every time she wounded him, multiple new Raktabijas would be created. The goddess Chandika helped Kali kill all the clone Raktabijas and eventually killed Raktabija himself. The Chandika app is designed to help you kill the profusion of unused virtual resources that accumulate in a typical cloud environment.

# Public domain

This project is in the worldwide [public domain](LICENSE.md). As stated in [CONTRIBUTING](CONTRIBUTING.md):

> This project is in the public domain within the United States, and copyright and related rights in the work worldwide are waived through the [CC0 1.0 Universal public domain dedication](https://creativecommons.org/publicdomain/zero/1.0/).

> All contributions to this project will be released under the CC0 dedication. By submitting a pull request, you are agreeing to comply with this waiver of copyright interest.
