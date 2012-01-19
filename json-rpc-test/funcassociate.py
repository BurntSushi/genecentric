# A simple example to get started.
# This works!
import urllib2, json

service_url = 'http://llama.mshri.on.ca/cgi/funcassociate/serv'
req = urllib2.Request(service_url)
req.add_header('Content-type', 'application/json')

data = json.dumps({ 'method': 'available_species',
                    'id': 0,
                    'jsonrpc': '2.0',
                  })

f = urllib2.urlopen(req, data)
raw_response = f.read()
response = json.loads(raw_response)

print response

