import re

with open('backend/database/seeders/ComprehensiveRealisticCostSeeder.php', 'r') as f:
    content = f.read()
    
# Find all items
items = re.findall(r"\['name'\s*=>\s*'([^']+)',\s*'amount'\s*=>\s*(\d+),\s*'currency'\s*=>\s*'([^']+)',\s*'description'\s*=>\s*'([^']+)'", content)

for name, amount, curr, desc in items:
    # check for plus or multiple numbers
    numbers = re.findall(r'\d+', desc.replace(',', ''))
    if len(numbers) > 1 and int(amount) < 100:
        print(f"Suspicious: {amount} | Desc: {desc} | Name: {name}")

