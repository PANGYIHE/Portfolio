import pandas as pd
from faker import Faker
import random
import os
from datetime import datetime, timedelta

fake = Faker()

num_transactions = 545668  
num_merchants = 113  

channel_to_type_mapping = {
    'TNG eWallet': 'E-wallet',
    'TNG eWallet Offline': 'E-wallet Offline',
    'DuitNow QR': 'E-wallet',
    'DuitNow QR Offline': 'E-wallet Offline',
    'FPX': 'Online Bank',
    'Mastercard': 'Credit Card Offline',
    'Visa': 'Credit Card Offline',
    'Apple Pay': 'Credit Card Online',
    'Google Pay': 'Credit Card Online',
    'PayLater by Grab': 'Buy Now Pay Later',
    'Shopee PayLater': 'Buy Now Pay Later'
}

channel_weights = {
    'TNG eWallet': 0.15,
    'TNG eWallet Offline': 0.08,
    'DuitNow QR': 0.12,
    'DuitNow QR Offline': 0.08,
    'FPX': 0.12,
    'Visa': 0.06,
    'Mastercard': 0.06,
    'Apple Pay': 0.02,
    'Google Pay': 0.02,
    'PayLater by Grab': 0.02,
    'Shopee PayLater': 0.02
}

currency_weights = {
    'MYR': 0.75,
    'SGD': 0.08,
    'THB': 0.07,
    'USD': 0.03,
    'GBP': 0.01,
    'CNY': 0.06
}

currency_to_myr = {
    "MYR": 1,
    "SGD": 0.3042,
    "THB": 7.5380,
    "USD": 0.2372,
    "GBP": 0.1753,
    "CNY": 1.6897
}


refund_reason_weights = {
    'Product Defect': 0.25,
    'Wrong Item Sent': 0.3,
    'Customer Dissatisfaction': 0.1,
    'Delayed Delivery': 0.15,
    'Fraudulent Transaction': 0.05,
    'Other': 0.15
}

# Merchants Data
merchants = []
merchant_ids = [fake.company() for _ in range(num_merchants)]
industries = ["Electronics", "Apparel", "Food & Beverage", "Travel", "Health & Beauty", "Home Goods"]

for merchant in merchant_ids:
    merchants.append({
        'merchant_id': merchant,
        'leader_name': fake.name(),
        'create_date': fake.date_between(datetime(2022,1,1), datetime(2025,12,31)),
        'address': fake.address().replace("\n", ", "),
        'country': random.choice(['Malaysia','Singapore','Thailand','United States','United Kingdom','China']),
        'email': fake.company_email(),
        'phone': fake.phone_number(),
        'industry': random.choice(industries)
    })

merchants_df = pd.DataFrame(merchants)

merchant_weights = [round(random.triangular(1, 10, 5), 2) for _ in merchant_ids]

# --- Transactions ---
transactions = []
for _ in range(num_transactions):
    tran_id = fake.uuid4()
    channel = random.choices(list(channel_weights.keys()), weights=channel_weights.values())[0]
    currency = random.choices(list(currency_weights.keys()), weights=currency_weights.values())[0]

    years = [2022, 2023, 2024, 2025]
    year_weights = [1, 1.2, 1.8, 1.5]  

    year = random.choices(years, weights=year_weights)[0]

    start = datetime(year, 1, 1)
    end = datetime(year, 12, 31, 23, 59, 59)

    trans_date = fake.date_time_between(start_date=start, end_date=end)

    if trans_date > (datetime(2025,12,31) - timedelta(days=7)):
        status = random.choice(['pending', 'recorded'])
    else:
        status = random.choices(['completed','cancelled','failed'], weights=[0.94,0.04,0.02])[0]

    amount_myr = round(random.triangular(3, 80, 30), 2)
    amount_in_currency = round(amount_myr / currency_to_myr[currency], 2)

    transactions.append({
        'tran_id': tran_id,
        'merchant_id': random.choices(merchant_ids, weights=merchant_weights)[0],
        'channel': channel,
        'amount': amount_in_currency,
        'currency': currency,
        'date': trans_date,
        'status': status
    })

transactions_df = pd.DataFrame(transactions)

# --- Refunds ---
refunds = []

eligible_txns = transactions_df[
    transactions_df['status'].isin(['completed','recorded'])
]

refund_sample = eligible_txns.sample(frac=0.03, random_state=42)

cancelled_txns = transactions_df[
    transactions_df['status'] == 'cancelled'
]

refund_txns = pd.concat([refund_sample, cancelled_txns])

for _, row in refund_txns.iterrows():

    tran_id = row['tran_id']
    tran_amount = row['amount']
    tran_date = row['date']
    channel = row['channel']

    # --- Delay rules ---
    channel_type = channel_to_type_mapping.get(channel, "")

    create_date = tran_date + timedelta(days=random.randint(0,3))

    if channel_type == "Online Bank":
        delay_days = random.randint(7,10)
    elif channel_type == "E-wallet":
        delay_days = random.randint(3,7)
    else:
        delay_days = random.randint(0,3)

    refund_date = create_date + timedelta(days=delay_days)

    # --- Status rule ---
    if row['status'] == 'cancelled':
            status = 'completed'
            create_date = tran_date
            refund_date = tran_date
    else:
        if create_date > datetime(2025,12,31):
            status = 'pending'
            create_date = tran_date
            refund_date = ''
        else:
            if refund_date > datetime(2025,12,31):
                status = 'pending'
                refund_date = ''
            else:
                status = random.choices(
                    ['completed','cancelled','failed'],
                    weights=[0.8,0.19,0.01]
                )[0]

    # --- Partial vs Full Refund ---
    if random.random() < 0.143:  
        refund_amount = round(
            tran_amount * random.uniform(0.5, 0.9),
            2
        )
    else:
        refund_amount = tran_amount

    refunds.append({
        'refund_id': fake.uuid4(),
        'tran_id': tran_id,
        'channel': channel,
        'amount': refund_amount,
        'create_date': create_date,
        'refund_date': refund_date,
        'reason': random.choices(
            list(refund_reason_weights.keys()),
            weights=refund_reason_weights.values()
        )[0],
        'status': status
    })

refunds_df = pd.DataFrame(refunds)

# --- Transaction Info ---
transaction_info = []
for _, row in transactions_df.iterrows():
    region = "Domestic" if row['currency'] == "MYR" else "Foreign"
    channel_type = channel_to_type_mapping.get(row['channel'], "Other")

    card_brands, card_type = None, None
    if "Credit Card" in channel_type:
        card_brands = random.choice(['Visa','Mastercard','UnionPay','American Express'])
        card_type = random.choices(['debit','credit'], weights=[0.65,0.35])[0]

    if region == "Domestic":
        country = 'Malaysia'
    else:
        country = random.choices(
            ['Singapore','Thailand','China','United States','United Kingdom'],
            weights=[0.8,0.7,0.6,0.3,0.1]
        )[0]

    transaction_info.append({
        'tran_id': row['tran_id'],
        'country': country,
        'card_type': card_type,
        'card_brands': card_brands,
        'region': region,
        'channel_type': channel_type
    })

transaction_info_df = pd.DataFrame(transaction_info)

# --- Save ---
output_folder = 'Transaction and Refund Analysis/csv_file'
os.makedirs(output_folder, exist_ok=True)

merchants_df.to_csv(f'{output_folder}/merchants.csv', index=False)
transactions_df.to_csv(f'{output_folder}/transactions.csv', index=False)
refunds_df.to_csv(f'{output_folder}/refunds.csv', index=False)
transaction_info_df.to_csv(f'{output_folder}/transaction_info.csv', index=False)

print("Data generated successfully!")
