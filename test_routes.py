import urllib.request
import urllib.error

print("Testing Flask routes...\n")

routes_to_test = [
    '/',
    '/login',
    '/register',
    '/backend-test',
    '/login-test',
    '/status',
    '/api/interests'
]

for route in routes_to_test:
    try:
        response = urllib.request.urlopen(f'http://127.0.0.1:5000{route}', timeout=3)
        status = response.status
        content_type = response.headers.get('Content-Type', 'unknown')
        html = response.read().decode()
        
        if status == 200:
            print(f"✅ {route:<20} -> Status {status} | {len(html)} bytes")
        else:
            print(f"⚠️  {route:<20} -> Status {status}")
            
    except urllib.error.HTTPError as e:
        print(f"❌ {route:<20} -> HTTP Error {e.code}")
    except Exception as e:
        print(f"❌ {route:<20} -> {str(e)}")

print("\n✅ All routes should return status 200")
