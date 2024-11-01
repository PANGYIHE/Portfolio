using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class RandomSpawnEnemy : MonoBehaviour
{
    public GameObject theEnemy;
    public int x1, x2, z1, z2;
    private int xPos, zPos;
    public int enemyCount;

    void Start()
    {
        StartCoroutine(EnemyDrop());
    }
    IEnumerator EnemyDrop()
    {
        while (enemyCount < 10)
        {

            xPos = Random.Range(x1, x2);
            zPos = Random.Range(z1, z2);
            Instantiate(theEnemy, new Vector3(xPos, 1, zPos), Quaternion.identity);
            yield return new WaitForSeconds(5.0f);
            enemyCount += 1;
        }
    }
}
