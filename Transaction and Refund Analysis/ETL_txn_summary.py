import pandas as pd
import sqlite3
from datetime import datetime, timedelta

conn = sqlite3.connect("Transaction and Refund Analysis/payments.db")

def convert_currency_to_MYR(dataframe):
    rates = {
        "MYR": 1,
        "SGD": 0.3042,
        "THB": 7.5380,
        "USD": 0.2372,
        "GBP": 0.1753,
        "CNY": 1.6897
    }

    dataframe = dataframe.copy()

    dataframe["amount"] = round(dataframe.apply(
        lambda x: x["amount"] / rates.get(x["currency"], 1), axis=1
    ), 2)

    dataframe["currency"] = 'MYR'
    
    return dataframe

# --- Extract ---
# yesterday = datetime.now().date() - timedelta(days=1)
# last_week = yesterday - timedelta(days=6)
yesterday = datetime(2025, 12, 31)
last_week = yesterday - timedelta(days=6)

yesterday_str = yesterday.strftime('%Y-%m-%d')
last_week_str = last_week.strftime('%Y-%m-%d')

query = f"""
SELECT 
    DATE(date) as date,
    merchant_id,
    tran_id,
    channel,
    amount,
    currency
FROM transactions
WHERE status IN ('completed','recorded')
AND date BETWEEN '{last_week_str} 00:00:00' AND '{yesterday_str} 23:59:59'
"""

result_df = pd.read_sql_query(query, conn)

# --- Transform ---
converted_df = convert_currency_to_MYR(result_df)

converted_df = converted_df.groupby(["date", "merchant_id", "channel"]).agg(
    txn_count=("tran_id", 'count'),
    total_amt=("amount", 'sum')
).reset_index()

# --- Load ---
converted_df.to_sql(
    "txn_summary",
    conn,
    index=False,
    if_exists="replace"   # overwrite every run
)

conn.commit()
conn.close()

file = f"Transaction and Refund Analysis/csv_file/{last_week_str} to {yesterday.strftime('%d')} txn_summary.csv"
converted_df.to_csv(file, index=False)

print("Report generated:", file)