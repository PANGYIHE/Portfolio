import pandas as pd
import sqlite3
from datetime import datetime, timedelta

mer_df = pd.read_csv("--OTHER CODES--/transaction_refund_analysis/csv_file/merchants.csv")
refund_df = pd.read_csv("--OTHER CODES--/transaction_refund_analysis/csv_file/refunds.csv")
tran_info_df = pd.read_csv("--OTHER CODES--/transaction_refund_analysis/csv_file/transaction_info.csv")
tran_df = pd.read_csv("--OTHER CODES--/transaction_refund_analysis/csv_file/transactions.csv")

conn = sqlite3.connect("--OTHER CODES--/transaction_refund_analysis/payments.db")

mer_df.to_sql("merchants", conn, index=False, if_exists='replace')
refund_df.to_sql("refunds", conn, index=False, if_exists='replace')
tran_info_df.to_sql("transaction_info", conn, index=False, if_exists='replace')
tran_df.to_sql("transactions", conn, index=False, if_exists='replace')

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
yesterday = datetime(2025, 12, 31)
last_week = yesterday - timedelta(days=7)

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

file = "--OTHER CODES--/transaction_refund_analysis/weekly_txn_summary.csv"
converted_df.to_csv(file, index=False)

print("Report generated:", file)