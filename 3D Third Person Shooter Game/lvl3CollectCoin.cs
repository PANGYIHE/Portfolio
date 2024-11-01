using System.Collections;
using System.Collections.Generic;
using TMPro;
using UnityEngine;

public class CollectCoin : MonoBehaviour
{
    //public AudioClip collectSound;
    public GameObject collectEffect;
    public TextMeshProUGUI moneyText;
    public static int money = 0;

    // Use this for initialization
    void Start()
    {
        moneyText = GameObject.Find("Coins").GetComponent<TextMeshProUGUI>();
    }

    // Update is called once per frame
    void Update()
    {
        transform.Rotate(Vector3.up * 100 * Time.deltaTime);
        moneyText.text = "Coin: " + money + "/5000$";
    }

    void OnTriggerEnter(Collider other)
    {
        if (other.CompareTag("Player"))
        {
            if (collectEffect)
                Instantiate(collectEffect, transform.position, Quaternion.identity);

            // Ensure the tag checking logic is correct
            if (gameObject.CompareTag("goldCoin"))
            {
                money += 100;
            }
            else if (gameObject.CompareTag("silverCoin"))
            {
                money += 50;
            }

            Destroy(gameObject);
        }
    }
}
