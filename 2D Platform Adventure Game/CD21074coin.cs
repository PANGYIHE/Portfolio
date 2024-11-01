using System.Collections;
using System.Collections.Generic;
using Unity.VisualScripting;
using UnityEngine;

public class coin : MonoBehaviour
{
    public Transform playerTransform;
    public float moveSpeed = 5f;

    coinmove coinMoveScript;

    // Start is called before the first frame update
    void Start()
    {
        playerTransform = GameObject.FindGameObjectWithTag("Player").transform;
        coinMoveScript = gameObject.GetComponent<coinmove>();
    }

    private void OnTriggerEnter(Collider other)
    {
        if (other.gameObject.tag == "Coin Detector")
        {
            coinMoveScript.enabled = true;
        }
    }
}
