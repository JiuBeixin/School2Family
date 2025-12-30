import requests

url = "https://school2family.vercel.app/api/wechat"
data = {"message": "Hello from Python"}
response = requests.post(url, json=data)

print(response.status_code)
print(response.text)